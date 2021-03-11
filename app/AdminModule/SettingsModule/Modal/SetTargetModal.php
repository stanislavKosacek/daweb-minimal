<?php


namespace App\AdminModule\SettingsModule\Modal;



use App\AdminModule\SettingsModule\Components\SetRoleFormFactory;
use App\AdminModule\SettingsModule\Components\SetTargetFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\Router\Target\Target;
use App\Model\User\Role\Role;

interface SetTargetModalFactory
{

	public function create(?Target $target = NULL): SetTargetModal;
}



class SetTargetModal extends BaseModal
{

	/** @var SetTargetFormFactory */
	private $factory;

	/** @var Target|null */
	private $target;



	public function __construct(?Target $target = NULL, SetTargetFormFactory $factory)
	{
		$this->target = $target;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->target);
		$control->onSuccess[] = function () {
			$this->flashMessage($this->target ? "URL byla upravena" : "URL byla přidán", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetTargetModal.latte");
		$this->template->title = $this->target ? "Upravit URL" : "Přidat URL";
		$this->template->render();
	}
}
