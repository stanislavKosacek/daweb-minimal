<?php


namespace App\Model\Comment\Types;


use App\Model\Comment\Comment;
use App\Model\Comment\IComment;
use App\Model\Page\Page\Page;

/**
 * @property Page|null $relatedId {m:1 Page::$comments}
 */
class PageComment extends Comment implements IComment
{

	public function onCreate(): void
	{
		parent::onCreate();
		$this->type = Comment::TYPE_PAGE;
	}



	public static function getTypeStatic(): string
	{
		return self::TYPE_PAGE;
	}



	/**
	 * @return Page
	 */
	public function getPage(): Page
	{
		return $this->page;
	}



	/**
	 * @param Page $page
	 */
	public function setPage(Page $page): void
	{
		$this->relatedId = $page;
	}
}
