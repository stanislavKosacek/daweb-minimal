<?php


namespace App\AdminModule\SettingsModule\Components;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Router\Redirect\Redirect;
use App\Model\Router\Redirect\RedirectRepository;
use Nette\SmartObject;

interface SetRedirectFormFactory
{

	public function create(?Redirect $redirect = NULL): SetRedirectForm;
}



class SetRedirectForm
{

	use SmartObject;

	/** @var Redirect|null */
	private $redirect;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var Orm */
	private $orm;

	/** @var RedirectRepository */
	private $redirectRepository;

	/** @var array */
	public $onSuccess = [];



	public function __construct(?Redirect $redirect = NULL, BaseFormFactory $baseFormFactory, Orm $orm, RedirectRepository $redirectRepository)
	{
		$this->redirect = $redirect;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
		$this->redirectRepository = $redirectRepository;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addText("from", "Z")
			 ->setRequired("Zadejte Z");
		$form->addText("to", "Na")
			 ->setRequired("Zadejte Na");


		$form->addSubmit("send", $this->redirect ? "Upravit" : "Přidat");

		$form->onSuccess[] = [$this, "processForm"];

		$defaults = [];
		if ($this->redirect) {
			$defaults["from"] = $this->redirect->getFrom();
			$defaults["to"] = $this->redirect->getTo();
		}
		$form->setDefaults($defaults);

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$redirect = $this->redirect ?? new Redirect();

		$redirect->setFrom($values->from);
		$redirect->setTo($values->to);

		$this->orm->persistAndFlush($redirect);
		$this->onSuccess();
	}


}
