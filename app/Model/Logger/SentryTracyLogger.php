<?php

namespace App\Model\Logger;

use Nette\Application\Application;
use Nette\Security\User;
use Nette\Utils\Strings;
use Sentry\Event;
use WhichBrowser\Parser;
use function Sentry\captureEvent;
use function Sentry\configureScope;
use function Sentry\init;
use Sentry\Severity;
use Sentry\State\Scope;
use Tracy\Debugger;
use Tracy\Dumper;
use Tracy\ILogger;
use Tracy\Logger;

class SentryTracyLogger implements ILogger
{

	/** @var Logger */
	private $parentLogger;

	/** @var User */
	private $securityUser;

	/** @var Application */
	private $application;

	/** @var string */
	private $dsn;



	public function __construct(array $sentryOptions, User $securityUser, Application $application)
	{
		$this->parentLogger = Debugger::getLogger();

		init($sentryOptions);
		$this->dsn = $sentryOptions["dsn"];
		$this->securityUser = $securityUser;
		$this->application = $application;
	}



	public function log($message, $priority = self::INFO)
	{
		$this->parentLogger->log($message, $priority);
		$this->logToSentry($message, $priority);
	}



	public function logToSentry($message, $priority)
	{
		$severity = $this->getSeverityFromPriority($priority);
		if (!$severity) {
			return;
		}


		$payload = [
			"message" => $message,
			"level" => $priority,
		];


		if ($message instanceof \Throwable) {
			$payload["exception"] = $message;
			$payload["message"] = $message->getMessage();
			$this->addExceptionFile($message);
		}

		$parser = new Parser(getallheaders());
		$this->addUserData();
		$this->addAppRequests();

		configureScope(function (Scope $scope) use ($parser) {
			$scope->setContext("device", ["type" => "device", "name" => $parser->device->getManufacturer(), "model" => $parser->device->getModel()]);
			$scope->setContext("browser", ["type" => "browser", "name" => $parser->browser->getName(), "version" => $parser->browser->getVersion()]);
			$scope->addEventProcessor(function (Event $event, $payload) use ($parser) {
				$event->setRequest($this->getHttpData());

				$event->getServerOsContext()->setData([
					'name' => $parser->os->getName(),
					'version' => $parser->os->getVersion(),
					'build' => "",
					'kernel_version' => "",
				]);

				return $event;
			});

			$scope->setTag("os", $parser->os->toString());
			$scope->setTag("os.name", $parser->os->getName());
			$scope->setTag("sapi", php_sapi_name());

		});

		captureEvent($payload);
	}



	private function getSeverityFromPriority($priority): ?Severity
	{
		switch ($priority) {
			case ILogger::WARNING:
				return Severity::warning();
			case ILogger::ERROR:
				return Severity::error();
			case ILogger::EXCEPTION:
			case ILogger::CRITICAL:
				return Severity::fatal();
			default:
				return NULL;

		}
	}



	private function addUserData()
	{
		configureScope(function (Scope $scope): void {
			/** @var \App\Model\User\User\User $identity */
			if ($this->securityUser->isLoggedIn() and $identity = $this->securityUser->getIdentity()) {
				$scope->setUser([
					'id' => $this->securityUser->getIdentity()->getId(),
					'email' => $identity->getEmail(),
					'username' => $identity->getName() . " " . $identity->getSurname(),
					'ip_address' => self::getServerVariable('REMOTE_ADDR'),
				]);
			} else {
				$scope->setUser([
					'id' => session_id(),
					'ip_address' => self::getServerVariable('REMOTE_ADDR'),
				]);
			}
		});
		$this->getSessionData();
	}



	public function getSessionData()
	{
		try {
			$sessions = [];
			foreach ($_SESSION ?? [] as $k => $val) {
				if (!$val) {
					continue;
				}
				if ($k === '__NF') {
					$val = isset($val['DATA']) ? $val['DATA'] : NULL;
				} elseif ($k === '_tracy') {
					continue;
				}
				if (isset($val["locale"]) and isset($val["locale"]["locale"])) {
					configureScope(function (Scope $scope) use ($val): void {
						$scope->setTag('locale', $val["locale"]["locale"]);
					});
				}
				$sessions = array_merge($sessions, array_map(function ($val) {
					if (is_scalar($val)) {
						return $val;
					} elseif (is_array($val)) {
						return json_encode($val);
					}

					return Dumper::toText($val);
				}, $val));
			}

			if ($sessions) {
				$sessions = array_filter($sessions, function ($key) {
					return preg_match_all("~(admin|grid)~", Strings::lower($key)) === 0;
				}, ARRAY_FILTER_USE_KEY);
			}

			configureScope(function (Scope $scope) use ($sessions): void {
				$scope->setExtra('session_data', $sessions);
			});

		} catch (\Exception $e) {
		}
	}



	private function addExceptionFile($e)
	{
		configureScope(function (Scope $scope) use ($e): void {
			$scope->setExtra('tracyFile', basename($this->parentLogger->getExceptionFile($e)));
		});
	}



	private function addAppRequests()
	{
		try {
			$app_requests = [];
			foreach ($this->application->getRequests() as $request) {
				$app_requests[] = [
					'presenter' => $request->presenterName,
					'method' => $request->method,
					'parameters' => array_map(function ($val) {
						return is_scalar($val) ? $val : Dumper::toText($val);
					}, $request->getParameters()),
				];

			}
			configureScope(function (Scope $scope) use ($app_requests): void {
				$scope->setExtra('App requests', $app_requests);
			});
		} catch (\Exception $e) {
		}
	}



	public function getDsn(): string
	{
		return $this->dsn;
	}



	private static function getServerVariable($key)
	{
		if (isset($_SERVER[$key])) {
			return $_SERVER[$key];
		}

		return '';
	}



	/**
	 * Return the URL for the current request
	 * @return string|null
	 */
	private function getCurrentUrl()
	{
		// When running from commandline the REQUEST_URI is missing.
		if (!isset($_SERVER['REQUEST_URI'])) {
			return NULL;
		}

		// HTTP_HOST is a client-supplied header that is optional in HTTP 1.0
		$host = (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST']
			: (!empty($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR']
				: (!empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '')));

		$hasNonDefaultPort = !empty($_SERVER['SERVER_PORT']) && !in_array((int) $_SERVER['SERVER_PORT'], [80, 443]);
		if ($hasNonDefaultPort && !preg_match('#:[0-9]*$#', $host)) {
			$host .= ':' . $_SERVER['SERVER_PORT'];
		}

		$httpS = $this->isHttps() ? 's' : '';

		return "http{$httpS}://{$host}{$_SERVER['REQUEST_URI']}";
	}



	/**
	 * Was the current request made over https?
	 * @return bool
	 */
	private function isHttps()
	{
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
			return TRUE;
		}

		if (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
			return TRUE;
		}

		if (!empty($this->trust_x_forwarded_proto) &&
			!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
			$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
			return TRUE;
		}

		return FALSE;
	}



	protected function getHttpData()
	{
		$headers = [];

		foreach ($_SERVER as $key => $value) {
			if (0 === strpos($key, 'HTTP_')) {
				$header_key =
					str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
				$headers[$header_key] = $value;
			} elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH']) && $value !== '') {
				$header_key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
				$headers[$header_key] = $value;
			}
		}

		$result = [
			'method' => self::getServerVariable('REQUEST_METHOD'),
			'url' => $this->getCurrentUrl(),
			'query_string' => self::getServerVariable('QUERY_STRING'),
		];

		// dont set this as an empty array as PHP will treat it as a numeric array
		// instead of a mapping which goes against the defined Sentry spec
		if (!empty($_POST)) {
			$result['data'] = $_POST;
		} elseif (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {
			$raw_data = $this->getInputStream() ?: FALSE;
			if ($raw_data !== FALSE) {
				$result['data'] = (array) json_decode($raw_data, TRUE) ?: NULL;
			}
		}
		if (!empty($_COOKIE)) {
			$result['cookies'] = $_COOKIE;
		}
		if (!empty($headers)) {
			$result['headers'] = $headers;
		}

		return $result;
	}



	/**
	 * Note: Prior to PHP 5.6, a stream opened with php://input can
	 * only be read once;
	 * @see http://php.net/manual/en/wrappers.php.php
	 */
	protected static function getInputStream()
	{
		if (PHP_VERSION_ID < 50600) {
			return NULL;
		}

		return file_get_contents('php://input');
	}

}
