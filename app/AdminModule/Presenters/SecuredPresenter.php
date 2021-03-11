<?php


namespace App\AdminModule\Presenters;


use App\Presenters\BasePresenter;

class SecuredPresenter extends BasePresenter
{

	protected function startup()
	{
		parent::startup();
		if (!$this->user->isLoggedIn() or !$this->user->getIdentity() or !$this->user->isInRole("admin")) {
				$this->user->logout(TRUE);
				$this->redirect(":Sign:in", ["backlink" => $this->storeRequest()]);
		}
	}



	public function beforeRender()
	{
		parent::beforeRender();
		if ($this->isAjax()) {
			$this->redrawControl("sidebar");
			$this->redrawControl("scripts");
		}
	}

}
