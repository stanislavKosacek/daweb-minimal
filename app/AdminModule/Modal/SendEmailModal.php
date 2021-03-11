<?php


namespace App\AdminModule\Modal;



use App\AdminModule\Components\SendEmailFormFactory;
use App\Model\Modal\BaseModal;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;

interface SendEmailModalFactory
{

	public function create(): SendEmailModal;
}



class SendEmailModal extends BaseModal
{


	/** @var SendEmailFormFactory */
	private $factory;

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var LinkGenerator */
	private $linkGenerator;



	public function __construct(SendEmailFormFactory $factory, ITemplateFactory $templateFactory, LinkGenerator $linkGenerator)
	{
		$this->factory = $factory;
		$this->templateFactory = $templateFactory;
		$this->linkGenerator = $linkGenerator;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create();
		$control->onSuccess[] = function () {
			$this->flashMessage("Email byl přidán do fronty k odeslání", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SendEmailModal.latte");
		$this->template->title = "Odeslat email";
		$this->template->render();
	}



	public function handleTest($body)
	{
		$body = $this->getPresenter()->getParameter("body");
		$template = $this->templateFactory->createTemplate();
		$template->setFile(__DIR__ . "/../../Model/Email/EmailType/EmptyEmail" . '/EmptyEmail.latte');

		$template->setTranslator($this->getPresenter()->translator);
		//$template->setFile(__DIR__ . "/EmptyEmail.latte");
		$template->add("lang", "cs");
		$template->getLatte()->addProvider('uiControl', $this->linkGenerator);
		$markdown = new \Parsedown();
		$template->getLatte()->addFilter("markdown", [$markdown, "text"]);


		$template->body = $body;

//		$this->payload->html = $template->renderToString();
//
//		$this->sendPayload();

		$this->presenter->sendJson($template->renderToString());
		$this->redirect("this");

	}
}
