<?php

namespace App\Components\Sign;

use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\SmartObject;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\ImageStorageException;
use WebChemistry\Images\Storage;

interface SignUpFormFactory
{

	function create(): SignUpForm;
}



class SignUpForm
{

	use SmartObject;

	/** @var UserRepository */
	private $userRepository;

	/**@var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];

	/** @var IImageStorage */
	private $storage;



	public function __construct(UserRepository $userRepository, BaseFormFactory $baseFormFactory, Orm $orm, IImageStorage $storage)
	{
		$this->userRepository = $userRepository;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
		$this->storage = $storage;
	}



	public function getForm()
	{
		$form = $this->baseFormFactory->create();
		$form->addText('name', 'Jméno')
			 ->setRequired("Vyplňte prosím jméno");
		$form->addText('surname', 'Příjmení')
			 ->setRequired("Vyplňte prosím příjmení");
		$form->addEmail('email', 'Email')
			 ->setRequired('Vyplňte prosím email');
		$password = $form->addPassword('password', 'Heslo', 50)
						 ->setRequired('Vyplňte prosím heslo');
		$form->addPassword('password2', 'Heslo znovu', 50)
			 ->setRequired('Vyplňte prosím heslo znovu')
			 ->addRule($form::EQUAL, 'Hesla se neshodují', $password);
		/*
		$form->addImageUpload('image', 'Obrázek')
			 ->setNamespace(User::getNamespace());
		*/
		$form->addSubmit('signUp', 'Registrovat se');


		$form->onSuccess[] = [$this, 'processForm'];
		$form->onValidate[] = [$this, "validateForm"];

		return $form;
	}



	public function validateForm(BaseForm $form)
	{
		if ($this->userRepository->emailExist($form->getValues()->email)) {
			$form->getPresenter()->getUser()->getStorage()->setIdentity(NULL);
			$form->getComponent("email")->addError("emailExist");
		}
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$user = new User();
		$user->setEmail($values->email);
		$user->setName($values->name);
		$user->setSurname($values->surname);
		//$user->setImage($this->storage->save($values->image)->getId());
		$user->changePassword(NULL, $values->password);

		$this->orm->persistAndFlush($user);

		$this->onSuccess($user);
	}
}
