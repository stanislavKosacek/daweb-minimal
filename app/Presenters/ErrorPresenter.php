<?php

namespace App\Presenters;


use Nette;
use Tracy\Debugger;


/**
 * Error presenter.
 */
class ErrorPresenter extends BasePresenter
{

	public function actionDefault($exception, $request)
	{
		if (!($exception instanceof Nette\Application\BadRequestException)) {
			$this->forward(":Error500:", ["exception" => $exception, "request" => $request]);
		}
	}



	/**
	 * @param \Exception
	 * @return void
	 */
	public function renderDefault($exception)
	{
		if ($exception instanceof Nette\Application\BadRequestException) {
			$code = $exception->getCode();
			// load template 403.latte or 404.latte or ... 4xx.latte
			$this->setView(in_array($code, [403, 404, 405, 410, 500]) ? $code : '4xx');
			// log to access.log
			Debugger::log("HTTP code $code: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}", 'access');
		}

		if ($this->isAjax()) { // AJAX request? Note this error in payload.
			$this->payload->error = TRUE;
			$this->sendPayload();
		}
	}

}
