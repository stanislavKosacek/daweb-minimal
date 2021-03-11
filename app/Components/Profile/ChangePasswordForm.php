<?php

namespace App\Components\Profile;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Security\InvalidOldPasswordException;
use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\SmartObject;

interface ChangePasswordFormFactory
{

	function create(User $user): ChangePasswordForm;
}



class ChangePasswordForm
{

	use SmartObject;

	/** @var User */
	private $user;

	/** @var UserRepository */
	private $userRepository;

	/**@var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];



	public function __construct(User $user, UserRepository $userRepository, Orm $orm, BaseFormFactory $baseFormFactory)
	{
		$this->user = $user;
		$this->userRepository = $userRepository;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm()
	{
		$form = $this->baseFormFactory->create();
		$form->addPassword("oldPassword", "Staré heslo")
			 ->setRequired();
		$password = $form->addPassword("password", "Nové heslo")
						 ->setRequired();
		$form->addPassword("password2", "Nové heslo pro kontrolu")
			 ->setRequired()
			 ->addRule($form::EQUAL, 'Hesla se neshodují', $password);

		$form->addSubmit('save', 'save');

		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();
		try {
			$this->user->changePassword($values->oldPassword, $values->password);
			$this->orm->persistAndFlush($this->user);
			$this->onSuccess();
		} catch (InvalidOldPasswordException $e) {
			$form->addError("Staré heslo nebylo zadáno správně");
		}

	}
}
