<?php

namespace App\Model\Czechitas\CodeShare;

use App\Model\Comment\Comment;
use App\Model\Comment\Types\CodeShareComment;
use App\Model\Helpers\Comments\ICommentRelatedEntity;
use App\Model\Orm;
use App\Model\User\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * CodeShare
 * @property int $id    {primary}
 * @property string $name
 * @property string|null $textCode
 * @property string|null $note
 * @property string|null $language
 * @property DateTimeImmutable $added
 * @property ManyHasOne|User|null $user    {m:1 User::$codeShares}
 * @property OneHasMany|CodeShareComment[] $comments    {1:m CodeShareComment::$relatedId, orderBy=added}
 */
class CodeShare extends Entity implements ICommentRelatedEntity
{

	/** @var Orm */
	private $orm;



	public function injectAdminPageBlock(Orm $orm)
	{
		$this->orm = $orm;
	}



	public function onCreate(): void
	{
		parent::onCreate();
		$this->added = new DateTimeImmutable();
	}



	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}



	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}



	/**
	 * @return string|null
	 */
	public function getTextCode(): ?string
	{
		return $this->textCode;
	}



	/**
	 * @param string|null $textCode
	 */
	public function setTextCode(?string $textCode): void
	{
		$this->textCode = $textCode;
	}



	/**
	 * @return string|null
	 */
	public function getNote(): ?string
	{
		return $this->note;
	}



	/**
	 * @param string|null $note
	 */
	public function setNote(?string $note): void
	{
		$this->note = $note;
	}



	/**
	 * @return User|ManyHasOne|null
	 */
	public function getUser()
	{
		return $this->user;
	}



	/**
	 * @param User|ManyHasOne|null $user
	 */
	public function setUser($user = NULL): void
	{
		$this->user = $user;
	}



	/**
	 * @return string|null
	 */
	public function getLanguage(): ?string
	{
		return $this->language;
	}



	/**
	 * @param string|null $language
	 */
	public function setLanguage(?string $language): void
	{
		$this->language = $language;
	}



	/**
	 * @return CodeShareComment[]|ICollection
	 */
	public function getComments(): ICollection
	{
		return $this->comments->toCollection()->findBy(["type" => CodeShareComment::TYPE_CODE_SHARE]);
	}



	public function createComment(string $commentText, User $user): Comment
	{
		$comment = new CodeShareComment();
		$comment->setCodeShare($this);
		$comment->setUser($user);
		$comment->setComment($commentText);

		$this->orm->persistAndFlush($comment);
	}
}
