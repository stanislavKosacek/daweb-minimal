<?php


namespace App\AdminModule\SettingsModule\Presenters;


use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Modal\SetRoleModalFactory;
use App\Model\User\Role\Role;
use App\Model\User\Role\RoleRepository;
use Nette\Application\BadRequestException;

class RolePresenter extends SecuredPresenter
{

	/** @var Role */
	protected $selectedRole;

	/** @var SetRoleModalFactory @autowire */
	protected $setRoleModalFactory;

	/** @var RoleRepository @autowire */
	protected $roleRepository;



	public function renderDefault()
	{
		$this->template->roles = $this->roleRepository->findRoleList();
	}



	public function actionAdd()
	{

		$modal = $this->setRoleModalFactory->create();
		$this->raiseModal($modal, 'addRoleModal', 'default', [], "default");

	}



	public function actionEdit($id)
	{
		if (!$id or !$this->selectedRole = $this->roleRepository->getRoleById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->setRoleModalFactory->create($this->selectedRole);
		$this->raiseModal($modal, 'editRoleModal', 'default', []);
	}
}
