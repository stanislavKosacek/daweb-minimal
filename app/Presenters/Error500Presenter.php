<?php


namespace App\Presenters;


use App\Model\Logger\SentryTracyLogger;
use Nette\Application\UI\Presenter;
use Tracy\Debugger;

class Error500Presenter extends Presenter
{


	public function renderDefault($exception)
	{
		$ILogger = Debugger::getLogger();
		if ($ILogger instanceof SentryTracyLogger) {
			$this->template->sentryDSN = $ILogger->getDsn();
		}

		$this->setView('500'); // load template 500.latte
		Debugger::log($exception, Debugger::EXCEPTION); // and log exception

		if ($this->isAjax()) { // AJAX request? Note this error in payload.
			$this->payload->error = TRUE;
			$this->sendPayload();
		}
	}


}
