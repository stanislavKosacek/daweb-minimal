<?php

namespace App\Model\Czechitas\HomeworkSolution;

use App\Model\Comment\Comment;
use App\Model\Comment\Types\CodeShareComment;
use App\Model\Comment\Types\HomeworkSolutionComment;
use App\Model\Comment\Types\PageComment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Helpers\Comments\ICommentRelatedEntity;
use App\Model\Orm;
use App\Model\User\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * HomeworkSolution
 * @property int $id    {primary}
 * @property string|null $note
 * @property ManyHasOne|User $user    {m:1 User::$homeworkSolutions}
 * @property ManyHasOne|HomeworkAssignment $homeworkAssignment    {m:1 HomeworkAssignment::$homeworkSolutions}
 * @property string $state {enum self::STATE_*}
 * @property DateTimeImmutable $added
 * @property DateTimeImmutable|null $edited
 * @property OneHasMany|PageComment[] $comments    {1:m PageComment::$relatedId, orderBy=added}
 */
class HomeworkSolution extends Entity implements ICommentRelatedEntity
{

	const STATE_UNDELIVERED = 'undelivered';
	const STATE_WAITING = 'waiting';
	const STATE_WRONG = 'wrong';
	const STATE_OK = 'ok';

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
		$this->state = self::STATE_UNDELIVERED;
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



	/**
	 * @return HomeworkAssignment|ManyHasOne
	 */
	public function getHomeworkAssignment()
	{
		return $this->homeworkAssignment;
	}



	/**
	 * @param HomeworkAssignment|ManyHasOne $homeworkAssignment
	 */
	public function setHomeworkAssignment($homeworkAssignment): void
	{
		$this->homeworkAssignment = $homeworkAssignment;
	}



	/**
	 * @return string
	 */
	public function getState(): string
	{
		return $this->state;
	}



	public function getTranslatedState(): string
	{
		return self::getStates()[$this->state];

	}



	/**
	 * @param string $state
	 */
	public function setState(string $state): void
	{
		$this->state = $state;
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



	public static function getStates(): array
	{
		return [
			self::STATE_UNDELIVERED => "Neodevzdáno",
			self::STATE_WAITING => "Čeká na kontrolu",
			self::STATE_OK => "Zkontrolováno",
			self::STATE_WRONG => "Úkol není správně",
		];
	}



	public function getComments(): ICollection
	{
		return $this->comments->toCollection()->findBy(["type" => Comment::TYPE_HOMEWORK_SOLUTION]);
	}



	public function createComment(string $commentText, User $user): Comment
	{
		$comment = new HomeworkSolutionComment();
		$comment->setHomeworkSolution($this);
		$comment->setUser($user);
		$comment->setComment($commentText);

		return $this->orm->persistAndFlush($comment);
	}
}
