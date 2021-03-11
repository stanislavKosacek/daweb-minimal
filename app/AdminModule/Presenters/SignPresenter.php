<?php


namespace App\AdminModule\Presenters;


use App\Components\Sign\SignInFormFactory;
use App\Presenters\BasePresenter;

class SignPresenter extends BasePresenter
{

	/** @var SignInFormFactory @autowire */
	protected $signInFormFactory;



	public function actionIn($sign = "in")
	{
		if ($this->user->isLoggedIn()) {
			if ($this->user->isInRole("admin")) {
				$this->redirect(":Admin:Homepage:default");
			}
		}

		$this->redirect(":Homepage:default");

		$this->template->sign = $sign;
		$this->template->locale = $this->locale;
	}



	protected function createComponentSignInForm()
	{
		$control = $this->signInFormFactory->create();
		$control->onSuccess[] = function () {
			if (!$this->user->getIdentity()) {
				$this->user->logout(TRUE);
				$this->redirect(':Admin:Sign:in');
			}
			if ($this->user->isInRole("admin")) {
				$this->flashMessage('admin.flashMessage.loginSuccess', 'success');
				$this->redirect(':Admin:Homepage:default');
			}
		};

		return $control->getForm();
	}



	public function actionOut()
	{
		$this->user->logout();
		$this->flashMessage('admin.flashMessage.logoutSuccess', 'success');
		$this->redirect(':Admin:Homepage:default');
	}

}
