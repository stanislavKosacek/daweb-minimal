<?php


namespace App\Model\Comment\Types;


use App\Model\Comment\Comment;
use App\Model\Comment\IComment;
use App\Model\Czechitas\CodeShare\CodeShare;

/**
 * @property CodeShare $relatedId {m:1 CodeShare::$comments}
 */
class CodeShareComment extends Comment implements IComment
{

	public function onCreate(): void
	{
		parent::onCreate();
		$this->type = Comment::TYPE_CODE_SHARE;
	}



	public static function getTypeStatic(): string
	{
		return self::TYPE_CODE_SHARE;
	}



	/**
	 * @return CodeShare
	 */
	public function getCodeShare(): CodeShare
	{
		return $this->relatedId;
	}



	/**
	 * @param CodeShare $codeShare
	 */
	public function setCodeShare(CodeShare $codeShare): void
	{
		$this->relatedId = $codeShare->getId();
	}
}
