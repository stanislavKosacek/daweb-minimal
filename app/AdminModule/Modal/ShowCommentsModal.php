<?php


namespace App\AdminModule\Modal;



use App\Model\Helpers\Comments\CommentProviderFactory;
use App\Model\Helpers\Comments\ICommentRelatedEntity;
use App\Model\Modal\BaseModal;

interface ShowCommentsModalFactory
{

	public function create(ICommentRelatedEntity $commentRelatedEntity): ShowCommentsModal;
}



class ShowCommentsModal extends BaseModal
{


	/** @var ICommentRelatedEntity */
	private $commentRelatedEntity;

	/** @var CommentProviderFactory */
	private $factory;



	public function __construct(ICommentRelatedEntity $commentRelatedEntity, CommentProviderFactory $factory)
	{
		$this->commentRelatedEntity = $commentRelatedEntity;
		$this->factory = $factory;
	}



	public function createComponentComments()
	{
		return $this->factory->create($this->commentRelatedEntity)->getAdminComponent();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/ShowCommentsModal.latte");
		$this->template->title = "Komentáře";
		$this->template->render();
	}
}
