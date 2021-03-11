<?php


namespace App\Model\Helpers\Comments;

use Nette\Application\UI\Control;

interface CommentProviderFactory
{

	public function create(ICommentRelatedEntity $commentRelatedEntity): CommentProvider;

}



class CommentProvider
{

	/** @var ICommentRelatedEntity */
	private $commentRelatedEntity;

	/** @var AdminCommentComponentFactory */
	private $adminCommentComponentFactory;

	/** @var FrontendCommentComponentFactory */
	private $frontendCommentComponentFactory;



	public function __construct(ICommentRelatedEntity $commentRelatedEntity, AdminCommentComponentFactory $adminCommentComponentFactory, FrontendCommentComponentFactory $frontendCommentComponentFactory)
	{
		$this->commentRelatedEntity = $commentRelatedEntity;
		$this->adminCommentComponentFactory = $adminCommentComponentFactory;
		$this->frontendCommentComponentFactory = $frontendCommentComponentFactory;
	}



	public function getAdminComponent(): Control
	{

		return $this->adminCommentComponentFactory->create($this->commentRelatedEntity);

	}



	public function getFrontendComponent(): Control
	{
		return $this->frontendCommentComponentFactory->create($this->commentRelatedEntity);

	}
}
