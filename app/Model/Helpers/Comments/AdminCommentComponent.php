<?php


namespace App\Model\Helpers\Comments;


use App\Model\Comment\Comment;
use App\Model\Comment\CommentRepository;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\User\User\User;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Control;
use Nette\Utils\Random;

interface AdminCommentComponentFactory
{

	public function create(ICommentRelatedEntity $commentRelatedEntity): AdminCommentComponent;

}



class AdminCommentComponent extends Control
{

	/** @var ICommentRelatedEntity */
	private $commentRelatedEntity;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var Comment|null */
	private $comment = NULL;

	/** @var CommentRepository */
	private $commentRepository;

	/** @var Orm */
	private $orm;



	public function __construct(ICommentRelatedEntity $commentRelatedEntity, BaseFormFactory $baseFormFactory, CommentRepository $commentRepository, Orm $orm)
	{
		$this->commentRelatedEntity = $commentRelatedEntity;
		$this->baseFormFactory = $baseFormFactory;
		$this->commentRepository = $commentRepository;
		$this->orm = $orm;
	}



	protected function createComponentForm()
	{
		$form = $this->baseFormFactory->create();
		$form->setAjax(TRUE);
		$form->addTextArea("comment")
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



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AdminCommentComponent.latte");
		$this->template->commentRelatedEntity = $this->commentRelatedEntity;
		$this->template->selectedComment = $this->comment;
		$this->template->render();
	}



	public function handleSelectComment($id)
	{
		$comment = $this->commentRepository->getCommentById($id);
		if (!$comment or $comment->getUser() != $this->getPresenter()->getUser()->getIdentity()) {
			throw new BadRequestException();
		}

		$this->comment = $comment;
	}



	public function createComponentEditComment()
	{
			$form = $this->baseFormFactory->create();
			$form->addTextArea("comment")
				 ->setHtmlAttribute("class", "markdown")
				 ->setDefaultValue($this->comment ? $this->comment->getComment() : NULL)
				 ->setHtmlId("markdown" . Random::generate(9))
				 ->setRequired();
			$form->addHidden("commentId", $this->comment ? $this->comment->getId() : NULL);
			$form->addSubmitButton("send", "Upravit");

			$form->onSuccess[] = [$this, "processEditCommentForm"];

			return $form;

	}



	public function processEditCommentForm(BaseForm $form)
	{
		$values = $form->getValues();
		$comment = $this->commentRepository->getCommentById($values->commentId);

		$comment->setComment($values->comment);
		$this->orm->persistAndFlush($comment);
		$this->presenter->flashMessage("Komentář byl upraven", "success");
		$this->presenter->redirect("this");
	}



	public function handleRemoveComment($id)
	{
		$comment = $this->commentRepository->getCommentById($id);
		if ($comment) {
			$this->orm->removeAndFlush($comment);
		}
		$this->presenter->flashMessage("Komentář byl odstraněn", "success");
		$this->redirect("this");

	}

}
