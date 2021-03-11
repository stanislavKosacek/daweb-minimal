<?php


namespace App\AdminModule\SettingsModule\Modal;



use App\AdminModule\SettingsModule\Components\SetRoleFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\User\Role\Role;

interface SetRoleModalFactory
{

	public function create(?Role $role = NULL): SetRoleModal;
}



class SetRoleModal extends BaseModal
{


	/** @var Role|null */
	private $role;

	/** @var SetRoleFormFactory */
	private $factory;



	public function __construct(?Role $role = NULL, SetRoleFormFactory $factory)
	{

		$this->role = $role;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->role);
		$control->onSuccess[] = function () {
			$this->flashMessage($this->role ? "Role byla upravena" : "Role byla přidán", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetRoleModal.latte");
		$this->template->title = $this->role ? "Upravit roli" : "Přidat roli";
		$this->template->render();
	}
}
