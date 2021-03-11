<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Sign\SignInFormFactory;
use App\Components\Sign\SignUpFormFactory;
use App\Model\Email\EmailType\NewRegistration\NewRegistration;
use App\Model\User\User\User;



final class SignPresenter extends BasePresenter
{

	/** @var SignUpFormFactory @autowire */
	protected $signUpFormFactory;

	/** @var SignInFormFactory @autowire */
	protected $signInFormFactory;

	/** @var NewRegistration @autowire */
	protected $newRegistration;


	/** @persistent */
	public $backlink;



	public function actionIn($backlink = NULL)
	{

		if ($backlink) {
			$this->backlink = $backlink;
		}
		if ($this->user->isLoggedIn()) {
			$this->redirect(":Homepage:default");
		}
	}



	public function actionUp()
	{
		if ($this->user->isLoggedIn()) {
			$this->redirect(":Homepage:default");
		}

	}



	public function actionOut()
	{
		$this->user->logout();
		$this->flashMessage('Odhláčení proběhlo úspěšně', 'success');
		$this->redirect(':Homepage:default');
	}



	public function createComponentSignInForm()
	{
		$control = $this->signInFormFactory->create();

		$control->onSuccess[] = function () {
			$this->flashMessage("Přihlášení proběhlo úspěšně");

			if ($this->backlink) {
				$this->restoreRequest($this->backlink);
			}
			$this->redirect("Homepage:default");
		};

		return $control->getForm();
	}



	public function createComponentSignUpForm()
	{
		$control = $this->signUpFormFactory->create();
		$control->onSuccess[] = function (User $user) {
			$this->newRegistration->createEmail(NULL, $user->getEmail(), NULL, "cs", ["user" => $user]);
			$this->flashMessage("Registrace byla úspěšná");
			$this->redirect("Sign:in");
		};

		return $control->getForm();
	}

}
