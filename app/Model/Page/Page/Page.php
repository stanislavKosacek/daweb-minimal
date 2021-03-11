<?php

namespace App\Model\Page\Page;

use App\Model\Comment\Comment;
use App\Model\Comment\Types\PageComment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Helpers\Comments\ICommentRelatedEntity;
use App\Model\Orm;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Router\Target\Target;
use App\Model\User\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\Resources\IFileResource;


/**
 * Page
 * @property int $id    {primary}
 * @property string $type {enum self::TYPE_*}
 * @property string $name
 * @property string|null $subtitle
 * @property string|null $image
 * @property DateTimeImmutable $added
 * @property DateTimeImmutable|null $published
 * @property Target|null $target  {1:1 Target, isMain=true, oneSided=true}
 * @property OneHasMany|PageBlock[] $pageBlocks    {1:m PageBlock::$page, orderBy=priority}
 * @property OneHasMany|PageComment[] $comments    {1:m PageComment::$relatedId, orderBy=added}
 * @property Lesson|null $lesson  {1:1 Lesson::$page}
 * @property HomeworkAssignment|null $homeworkAssignment  {1:1 HomeworkAssignment::$page}
 */
class Page extends Entity implements ICommentRelatedEntity
{

	const TYPE_DEFAULT = 'default';
	const TYPE_LESSON = 'lesson';
	const TYPE_HOMEWORK = 'homework';

	/** @var Orm */
	private $orm;

	/** @var IImageStorage */
	private $imageStorage;



	public function injectAdminPageBlock(Orm $orm, IImageStorage $imageStorage)
	{
		$this->orm = $orm;
		$this->imageStorage = $imageStorage;
	}



	public function onCreate(): void
	{
		parent::onCreate();
		$this->added = new DateTimeImmutable();
	}



	public static function getNamespace()
	{
		return "page";
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
	 * @return string|null
	 */
	public function getSubtitle(): ?string
	{
		return $this->subtitle;
	}



	/**
	 * @param string|null $subtitle
	 */
	public function setSubtitle(?string $subtitle): void
	{
		$this->subtitle = $subtitle;
	}



	public function getImage(): ?IFileResource
	{
		if ($this->image) {
			return $this->imageStorage->createResource($this->image);
		}

		return NULL;
	}



	/**
	 * @param string|null $image
	 */
	public function setImage(?string $image): void
	{
		$this->image = $image;
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
	public function getPublished(): ?DateTimeImmutable
	{
		return $this->published;
	}



	/**
	 * @param DateTimeImmutable|null $published
	 */
	public function setPublished(?DateTimeImmutable $published): void
	{
		$this->published = $published;
	}



	/**
	 * @return Target|null
	 */
	public function getTarget(): ?Target
	{
		return $this->target;
	}



	/**
	 * @param Target|null $target
	 */
	public function setTarget(?Target $target): void
	{
		$this->target = $target;
	}



	/**
	 * @return PageBlock[]|OneHasMany
	 */
	public function getPageBlocks()
	{
		return $this->pageBlocks;
	}



	public function getNextBlockPriority()
	{
		$block = $this->pageBlocks->toCollection()->resetOrderBy()->orderBy("priority", "DESC")->fetch();

		if ($block) {
			return $block->getPriority() + 1;
		}

		return 1;
	}



	/**
	 * @return PageComment[]|ICollection
	 */
	public function getComments(): ICollection
	{
		return $this->comments->toCollection()->findBy(["type" => Comment::TYPE_PAGE]);
	}



	public function createComment(string $commentText, User $user): Comment
	{
		$comment = new PageComment();
		$comment->setPage($this);
		$comment->setUser($user);
		$comment->setComment($commentText);

		return $this->orm->persistAndFlush($comment);

	}



	public function isPublished()
	{
		if (!$this->getPublished() or $this->getPublished() > new DateTimeImmutable()) {
			return FALSE;
		}

		return TRUE;
	}



	/**
	 * @return Lesson|null
	 */
	public function getLesson(): ?Lesson
	{
		return $this->lesson;
	}



	/**
	 * @param Lesson|null $lesson
	 */
	public function setLesson(?Lesson $lesson): void
	{
		$this->lesson = $lesson;
	}



	/**
	 * @return HomeworkAssignment|null
	 */
	public function getHomeworkAssignment(): ?HomeworkAssignment
	{
		return $this->homeworkAssignment;
	}



	/**
	 * @param HomeworkAssignment|null $homeworkAssignment
	 */
	public function setHomeworkAssignment(?HomeworkAssignment $homeworkAssignment): void
	{
		$this->homeworkAssignment = $homeworkAssignment;
	}


}
