<?php


namespace App\AdminModule\Components;



use App\Model\Email\EmailType\EmptyEmail\EmptyEmail;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use Contributte\Translation\Translator;
use Nette\SmartObject;

interface SendEmailFormFactory
{

	public function create(): SendEmailForm;
}



class SendEmailForm
{

	use SmartObject;


	/** @var Orm */
	private $orm;

	/** @var array */
	public $onSuccess = [];


	/** @var Translator */
	private $translator;

	/** @var EmptyEmail */
	private $emptyEmail;

	/** @var BaseFormFactory */
	private $baseFormFactory;



	public function __construct(Orm $orm, Translator $translator, EmptyEmail $emptyEmail, BaseFormFactory $baseFormFactory)
	{
		$this->orm = $orm;
		$this->translator = $translator;
		$this->emptyEmail = $emptyEmail;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addEmail("to", "Příjemce")
			 ->setRequired("Zadejte příjemce");

		$form->addText("subject", "subject")
			 ->setRequired("Zadejte předmět");
		$form->addTextArea("body", "body")
			->setHtmlId("markdownBody")
			 ->setHtmlAttribute("class", "markdown");

		$form->addSubmitButton("send", "Odeslat");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$this->emptyEmail->createEmail(NULL, $values->to, $values->subject, "cs", ["body" => $values->body]);

		$this->onSuccess();
	}

}
