<?php

declare(strict_types=1);

namespace App\Presenters;



use App\Components\Profile\ChangePasswordFormFactory;
use App\Components\Profile\EditUserFormFactory;
use Nette\Application\BadRequestException;

final class ProfilePresenter extends SecuredPresenter
{

	/** @var EditUserFormFactory @autowire */
	protected $editUserFormFactory;

	/** @var ChangePasswordFormFactory @autowire */
	protected $changePasswordFormFactory;



	public function renderDefault()
	{
		if (!$selectedUser = $this->user->getIdentity()) {
			throw new BadRequestException();
		}
		$this->template->selectedUser = $selectedUser;
	}


	public function renderChangePassword()
	{
		if (!$selectedUser = $this->user->getIdentity()) {
			throw new BadRequestException();
		}
		$this->template->selectedUser = $selectedUser;
	}






	public function createComponentEditUser()
	{
		$control = $this->editUserFormFactory->create($this->user->getIdentity());

		$control->onSuccess[] = function () {
			$this->flashMessage("Profil byl upraven");
			$this->redirect("this");
		};


		return $control->getForm();
	}


	public function createComponentChangePassword()
	{
		$control = $this->changePasswordFormFactory->create($this->user->getIdentity());

		$control->onSuccess[] = function () {
			$this->flashMessage("Heslo bylo změněno");
			$this->redirect("default");
		};

		return $control->getForm();
	}
}
