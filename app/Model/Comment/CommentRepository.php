<?php

namespace App\Model\Comment;

use App\Model\Comment\Types\CodeShareComment;
use App\Model\Comment\Types\HomeworkSolutionComment;
use App\Model\Comment\Types\PageComment;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Comment|NULL getById($id)
 */
class CommentRepository extends Repository
{


	public static function getEntityClassNames(): array
	{
		return [Comment::class, PageComment::class, CodeShareComment::class, HomeworkSolutionComment::class];
	}



	public function getEntityClassName(array $data): string
	{
		if ($data["type"] == Comment::TYPE_PAGE) {
			return PageComment::class;
		} elseif ($data["type"] == Comment::TYPE_CODE_SHARE) {
			return CodeShareComment::class;
		} elseif ($data["type"] == Comment::TYPE_HOMEWORK_SOLUTION) {
			return HomeworkSolutionComment::class;
		}

		return Comment::class;
	}



	/**
	 * @param $id
	 * @return Comment|null
	 */
	public function getCommentById($id): ?Comment
	{
		return $this->getById($id);
	}



	/**
	 * @return Comment[]|ICollection
	 */
	public function findCommentList(): ICollection
	{
		return $this->findAll();
	}

}
