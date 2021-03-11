<?php


namespace App\Model\Router;


use App\Model\Router\Redirect\RedirectRepository;
use App\Model\Router\Target\TargetRepository;
use Nette;
use Nette\Routing\Router;

interface DbRouterFactory
{

	public function create(): DbRouter;

}



class DbRouter implements Router
{

	/** @var TargetRepository */
	private $targetRepository;

	/** @var CurrentTarget */
	private $currentTarget;

	/** @var RedirectRepository */
	private $redirectRepository;



	public function __construct(TargetRepository $targetRepository, CurrentTarget $currentTarget, RedirectRepository $redirectRepository)
	{
		$this->targetRepository = $targetRepository;
		$this->currentTarget = $currentTarget;
		$this->redirectRepository = $redirectRepository;
	}



	function match(Nette\Http\IRequest $httpRequest): ?array
	{
		$relativeUrl = trim($httpRequest->getUrl()->relativeUrl, "/");

		$explodeDo = explode("?", $relativeUrl);

		$relativeUrl = $explodeDo[0];
		$do = $explodeDo[1] ?? NULL;

		if ($do) {
			return NULL;
		}


		if ($redirect = $this->redirectRepository->getRedirectByFrom($relativeUrl)) {
			header("HTTP/1.1 301 Moved Permanently");
			header('Location:' . $httpRequest->getUrl()->baseUrl . $redirect->getTo());
			exit();
		}


		if ($target = $this->targetRepository->getTargetBySlug($relativeUrl)) {
			$this->currentTarget->setCurrentTarget($target);
			if ($target->getParamName()) {
				return ["presenter" => $target->getPresenter(), "action" => $target->getAction(), "locale" => $target->getLocale(),
					$target->getParamName() => $target->getParamValue()];
			} else {
				return ["presenter" => $target->getPresenter(), "action" => $target->getAction(), "locale" => $target->getLocale()];
			}
		}
		$this->currentTarget->setCurrentTarget(NULL);

		return NULL;

		//return ["presenter" => "Homepage", ["action" => "default"], "ida" => 2, "id" => 2];
	}



	function constructUrl(array $params, Nette\Http\UrlScript $refUrl): ?string
	{
		if (isset($params["do"])) {
			return NULL;
		}
		$target = $this->targetRepository->getTargetByParams($params);
		if ($target) {
			$url = new Nette\Http\Url($refUrl->baseUrl);
			$url->setPath($url->getPath() . $target->getSlug());

			return $url->getAbsoluteUrl();
		}

		return NULL;
	}
}
