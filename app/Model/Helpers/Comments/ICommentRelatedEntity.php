<?php


namespace App\Model\Helpers\Comments;


use App\Model\Comment\Comment;
use App\Model\Comment\Types\CodeShareComment;
use App\Model\Comment\Types\PageComment;
use App\Model\User\User\User;
use Nextras\Orm\Collection\ICollection;

interface ICommentRelatedEntity
{

	/**
	 * @return Comment[]|PageComment[]|CodeShareComment[]|ICollection
	 */
	public function getComments(): ICollection;



	public function createComment(string $commentText, User $user): Comment;

}
