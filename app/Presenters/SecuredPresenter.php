<?php


namespace App\Presenters;



class SecuredPresenter extends BasePresenter
{


	protected function startup()
	{
		parent::startup();

		if ($this->user->isLoggedIn() and ($this->user->isInRole("member") or $this->user->isInRole("admin"))) {
			return;
		} else {
			$this->user->logout(TRUE);
			$this->redirect(':Sign:in', ["backlink" => $this->storeRequest()]);
		}
	}

	public function beforeRender()
	{
		parent::beforeRender();
		if ($this->isAjax()) {
			$this->redrawControl("scripts");
		}
	}

}
