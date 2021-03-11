<?php


namespace App\Model\Helpers\Comments;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use Nette\Application\UI\Control;
use Nette\Utils\Random;

interface FrontendCommentComponentFactory
{

	public function create(ICommentRelatedEntity $commentRelatedEntity): FrontendCommentComponent;

}



class FrontendCommentComponent extends Control
{

	/** @var ICommentRelatedEntity */
	private $commentRelatedEntity;

	/** @var BaseFormFactory */
	private $baseFormFactory;



	public function __construct(ICommentRelatedEntity $commentRelatedEntity, BaseFormFactory $baseFormFactory)
	{
		$this->commentRelatedEntity = $commentRelatedEntity;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/FrontendCommentComponent.latte");
		$this->template->commentRelatedEntity = $this->commentRelatedEntity;
		$this->template->render();
	}


	protected function createComponentForm()
	{
		$form = $this->baseFormFactory->create();
		$form->setAjax(TRUE);
		$form->addTextArea("comment", "Přidat komentář")
			 ->setHtmlAttribute("class", "markdown")
			 ->setHtmlId("markdown" . Random::generate(9))
			 ->setRequired("Napište komentář");

		$form->addSubmitButton("submit", "Přidat komentář");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();
		$this->commentRelatedEntity->createComment($values->comment, $this->presenter->getUser()->getIdentity());
		$this->presenter->flashMessage("Komentář byl přidán", "success");
		$this->presenter->redirect("this");
	}
}
