<?php

namespace App\Components\Sign;

use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use Nette\Security\User;
use Nette\SmartObject;
use Nette\Utils\Validators;

interface SignInFormFactory
{

	function create(): SignInForm;
}



class SignInForm
{

	use SmartObject;

	/** @var User */
	private $user;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];



	public function __construct(User $user, BaseFormFactory $baseFormFactory)
	{
		$this->user = $user;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm()
	{
		$form = $this->baseFormFactory->create();
		$form->setAjax(TRUE);
		$form->addEmail('email', 'Email:')
			 ->setHtmlAttribute('autofocus')
			 ->setRequired('Vyplňte prosím email');

		$form->addPassword('password', 'Heslo:')
			 ->setRequired('Vyplňte prosím heslo');
		$form->addSubmit('login', 'Přihlásit se');

		if ($this->user->getIdentity()) {
			$defaults = [
				"email" => NULL,
			];
			$form->setDefaults($defaults);
		}
		$form->onValidate[] = [$this, "validateForm"];
		$form->onSuccess[] = [$this, "processForm"];


		return $form;
	}



	public function validateForm(BaseForm $form)
	{
		$values = $form->getValues();

		if (!Validators::isEmail($values->email)) {
			$form->getComponent("email")->addError("notValidEmail");
		}
	}



	public function processForm(BaseForm $form)
	{
		$this->user->setExpiration('+30 days');
		$this->user->login($form->getValues()->email, $form->getValues()->password);
		$this->onSuccess();

	}
}
