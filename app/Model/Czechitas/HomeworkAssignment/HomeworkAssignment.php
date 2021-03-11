<?php

namespace App\Model\Czechitas\HomeworkAssignment;

use App\Model\Czechitas\HomeworkSolution\HomeworkSolution;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Page\Page\Page;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * HomeworkAssignment
 * @property int $id    {primary}
 * @property Page $page  {1:1 Page::$homeworkAssignment, isMain=true}
 * @property string|null $gitFolder
 * @property DateTimeImmutable|null $deadline
 * @property OneHasMany|HomeworkSolution[] $homeworkSolutions    {1:m HomeworkSolution::$homeworkAssignment}
 * @property ManyHasOne|Lesson $lesson    {m:1 Lesson::$homeworkAssignments}
 * @property DateTimeImmutable $added
 */
class HomeworkAssignment extends Entity
{

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
		$this->page = $page;
	}



	/**
	 * @return string|null
	 */
	public function getGitFolder(): ?string
	{
		return $this->gitFolder;
	}



	/**
	 * @param string|null $gitFolder
	 */
	public function setGitFolder(?string $gitFolder): void
	{
		$this->gitFolder = $gitFolder;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getDeadline(): ?DateTimeImmutable
	{
		return $this->deadline;
	}



	/**
	 * @param DateTimeImmutable|null $deadline
	 */
	public function setDeadline(?DateTimeImmutable $deadline): void
	{
		$this->deadline = $deadline;
	}



	/**
	 * @return HomeworkSolution[]|OneHasMany
	 */
	public function getHomeworkSolutions()
	{
		return $this->homeworkSolutions;
	}



	/**
	 * @return HomeworkSolution[]|ICollection
	 */
	public function getUncheckedHomeworkSolutions(): ICollection
	{
		return $this->homeworkSolutions->toCollection()->findBy(["state" => HomeworkSolution::STATE_WAITING]);
	}



	/**
	 * @return Lesson|ManyHasOne
	 */
	public function getLesson()
	{
		return $this->lesson;
	}



	/**
	 * @param Lesson|ManyHasOne $lesson
	 */
	public function setLesson($lesson): void
	{
		$this->lesson = $lesson;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}
}
