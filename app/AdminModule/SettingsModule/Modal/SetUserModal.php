<?php


namespace App\AdminModule\SettingsModule\Modal;



use App\AdminModule\SettingsModule\Components\SetUserFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\User\User\User;

interface SetUserModalFactory
{

	public function create(User $user = NULL): SetUserModal;
}



class SetUserModal extends BaseModal
{


	/** @var User|null */
	private $user;

	/** @var SetUserFormFactory */
	private $factory;



	public function __construct(User $user = NULL, SetUserFormFactory $factory)
	{

		$this->user = $user;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->user);
		$control->onSuccess[] = function () {
			$this->flashMessage($this->user ? "Uživatel byl upraven" : "Uživatel byl přidán", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetUserModal.latte");
		$this->template->title = $this->user ? "Upravit uživatele" : "Přidat uživatele";
		$this->template->render();
	}
}
