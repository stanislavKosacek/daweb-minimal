<?php


namespace App\Model\Comment\Types;


use App\Model\Comment\Comment;
use App\Model\Comment\IComment;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolution;

/**
 * @property HomeworkSolution|null $relatedId {m:1 HomeworkSolution::$comments}
 */
class HomeworkSolutionComment extends Comment implements IComment
{

	public function onCreate(): void
	{
		parent::onCreate();
		$this->type = Comment::TYPE_HOMEWORK_SOLUTION;
	}



	public static function getTypeStatic(): string
	{
		return self::TYPE_HOMEWORK_SOLUTION;
	}



	/**
	 * @return HomeworkSolution
	 */
	public function getHomeworkSolution(): HomeworkSolution
	{
		return $this->page;
	}



	/**
	 * @param HomeworkSolution $page
	 */
	public function setHomeworkSolution(HomeworkSolution $homeworkSolution): void
	{
		$this->relatedId = $homeworkSolution;
	}
}
