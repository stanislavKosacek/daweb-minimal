<?php


namespace App\AdminModule\SettingsModule\Components;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\User\Role\RoleRepository;
use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\InvalidArgumentException;
use Nette\SmartObject;
use Nextras\Dbal\Utils\DateTimeImmutable;
use WebChemistry\Images\IImageStorage;

interface SetUserFormFactory
{

	public function create(?User $user = NULL): SetUserForm;
}



class SetUserForm
{

	use SmartObject;

	/** @var User|null */
	private $user;

	/** @var Orm */
	private $orm;

	/** @var UserRepository */
	private $userRepository;

	/** @var RoleRepository */
	private $roleRepository;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];

	/** @var IImageStorage */
	private $storage;



	public function __construct(?User $user, BaseFormFactory $baseFormFactory, Orm $orm, UserRepository $userRepository, RoleRepository $roleRepository, IImageStorage $storage)
	{
		$this->user = $user;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
		$this->userRepository = $userRepository;
		$this->roleRepository = $roleRepository;
		$this->storage = $storage;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addGroup("Povinné");

		$form->addEmail("email", "Email")
			 ->setHtmlAttribute("autocomplete", "username")
			 ->setRequired("Zadejte email");

		$password = $form->addPassword("password", 'Nové heslo:');
		$password2 = $form->addPassword("password2", 'Nové heslo znovu:');
		if (!$this->user) {
			$password->setRequired('Vyplňte heslo');
			$password2->addRule($form::FILLED, 'Vyplňte heslo znovu pro kontrolu')
					  ->addRule($form::EQUAL, 'Hesla se neshodují', $password);
		} else {
			$password2->addConditionOn($password, $form::FILLED)
					  ->setRequired('Vyplňte heslo znovu pro kontrolu')
					  ->addRule($form::EQUAL, 'Hesla se neshodují', $password);
		}

		$roles = [];
		foreach ($this->roleRepository->findRoleList() as $role) {
			$roles[$role->getId()] = $role->getNameCs();
		}
		$form->addMultiSelect("role", "Role", $roles)
			 ->setTranslator(NULL);
		$form->addGroup("Nepovinné");
		$form->addText("name", "Jméno");
		$form->addText("surname", "Příjmení");
		$form->addText("dateBirth", "Datum narození")
			 ->setHtmlAttribute("class", "datepicker");
		$form->addText("phone", "Telefon");
		$form->addTextArea("note", "Poznámka");
		$form->addImageUpload("image", "Obrázek", User::getNamespace());


		$form->addSubmit("send", $this->user ? "Upravit" : "Přidat");
		$form->onSuccess[] = [$this, "processForm"];
		$form->onValidate[] = [$this, "validateForm"];

		$defaults = [];
		if ($this->user) {
			$defaults["email"] = $this->user->getEmail();
			$defaults["name"] = $this->user->getName();
			$defaults["surname"] = $this->user->getSurname();
			$defaults["dateBirth"] = $this->user->getDateBirth() ? $this->user->getDateBirth()->format("d.m.Y") : NULL;
			$defaults["phone"] = $this->user->getPhone();
			$defaults["note"] = $this->user->getNote();
			$roles = [];
			foreach ($this->user->getRolesEntity() as $role) {
				$roles[] = $role->getId();
			}
			$defaults["role"] = $roles;
		}
		$form->setDefaults($defaults);

		return $form;

	}



	public function validateForm(BaseForm $form)
	{
		if (!$this->user) {
			if ($this->userRepository->emailExist($form->getValues()->email)) {
				$form->getComponent("email")->addError("emailExist");
			}
			$password = $form->getValues()->password;
			$password2 = $form->getValues()->password2;
			if ($password != $password2) {
				$form->getComponent("password")->addError("passwordNotSame");
				$form->getComponent("password2")->addError("passwordNotSame");
			}
		} else {

			if ($this->userRepository->emailExist($form->getValues()->email) and $this->userRepository->getUserByEmail($form->getValues()->email) != $this->user) {
				$form->getComponent("email")->addError("emailExist");
			}
		}
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();
		$user = $this->user ? $this->user : new User();
		$user->setEmail($values->email);
		if (!$this->user) {
			$user->changePassword(NULL, $values->password);
		} else {
			if ($values->password) {
				$user->setPassword($values->password);
			}
		}

		foreach ($values->role as $roleId) {
			if ($role = $this->roleRepository->getRoleById($roleId)) {
				if (!in_array($role, $user->getRolesEntity()->getEntitiesForPersistence())) {
					$user->getRolesEntity()->add($this->roleRepository->getRoleById($roleId));
				}
			}
		}
		$user->setName($values->name);
		$user->setSurname($values->surname);
		if ($values->dateBirth != "") {
			$user->setDateBirth(new DateTimeImmutable($values->dateBirth));
		} else {
			$user->setDateBirth(NULL);
		}
		$user->setPhone($values->phone);
		$user->setNote($values->note);


		if ($values->image) {
			if ($this->user) {
				if ($this->user->getImage()) {
					$this->storage->delete($this->user->getImage());
				}
			}
			$user->setImage($this->storage->save($values->image)->getId());
		}


		$this->orm->persistAndFlush($user);
		$this->onSuccess();
	}


}
