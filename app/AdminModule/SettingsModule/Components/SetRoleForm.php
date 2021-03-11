<?php


namespace App\AdminModule\SettingsModule\Components;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\User\Role\Role;
use App\Model\User\Role\RoleRepository;
use Nette\SmartObject;

interface SetRoleFormFactory
{

	public function create(?Role $role = NULL): SetRoleForm;
}



class SetRoleForm
{

	use SmartObject;

	/** @var Role|null */
	private $role;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var Orm */
	private $orm;

	/** @var RoleRepository */
	private $roleRepository;

	/** @var array */
	public $onSuccess = [];



	public function __construct(?Role $role = NULL, BaseFormFactory $baseFormFactory, Orm $orm, RoleRepository $roleRepository)
	{
		$this->role = $role;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
		$this->roleRepository = $roleRepository;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addText("name", "Název")
			 ->setRequired("Zadejte název");
		$form->addText("nameCs", "Název ČJ")
			 ->setRequired("Zadejte název");


		$form->addSubmit("send", $this->role ? "Upravit" : "Přidat");
		$form->onSuccess[] = [$this, "processForm"];
		$form->onValidate[] = [$this, "validateForm"];

		$defaults = [];
		if ($this->role) {
			$defaults["name"] = $this->role->getName();
			$defaults["nameCs"] = $this->role->getNameCs();
		}
		$form->setDefaults($defaults);

		return $form;

	}



	public function validateForm(BaseForm $form)
	{
		if (!$this->role) {
			$role = $this->roleRepository->getRoleByName($form->getValues()->name);
			if ($role) {
				$form->getComponent("name")->addError("Role s tímto názvem již existuje");
			}
		}
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();
		$role = $this->role ?? new Role();

		$role->setName($values->name);
		$role->setNameCs($values->nameCs);

		$this->orm->persistAndFlush($role);
		$this->onSuccess();
	}


}
