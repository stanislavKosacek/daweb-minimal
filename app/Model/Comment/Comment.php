<?php

namespace App\Model\Comment;

use App\Model\User\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;


/**
 * Comment
 *
 * @property int $id    {primary}
 * @property string $type {enum self::TYPE_*}
 * @property string $comment
 * @property ManyHasOne|User $user    {m:1 User, oneSided=true}
 * @property DateTimeImmutable $added
 * @property DateTimeImmutable|null $edited
 */
abstract class Comment extends Entity
{

	const TYPE_PAGE = 'page';
	const TYPE_CODE_SHARE = 'codeShare';
	const TYPE_HOMEWORK_SOLUTION = 'homeworkSolution';



	public function onCreate(): void
	{
		parent::onCreate();
		$this->added = new DateTimeImmutable();
	}



	public function onBeforeUpdate(): void
	{
		parent::onBeforeUpdate();
		$this->edited = new DateTimeImmutable();
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
	public function getType(): string
	{
		return $this->type;
	}



	/**
	 * @param string $type
	 */
	public function setType(string $type): void
	{
		$this->type = $type;
	}



	/**
	 * @return string
	 */
	public function getComment(): string
	{
		return $this->comment;
	}



	/**
	 * @param string $comment
	 */
	public function setComment(string $comment): void
	{
		$this->comment = $comment;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getEdited(): ?DateTimeImmutable
	{
		return $this->edited;
	}



	/**
	 * @return User|ManyHasOne
	 */
	public function getUser()
	{
		return $this->user;
	}



	/**
	 * @param User|ManyHasOne $user
	 */
	public function setUser($user): void
	{
		$this->user = $user;
	}

}
