<?php

namespace App\Model\Czechitas\Lesson;

use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\LessonFile\LessonFile;
use App\Model\Czechitas\LessonTeamRole\LessonTeamRole;
use App\Model\Page\Page\Page;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * Lesson
 * @property int $id    {primary}
 * @property string $type {enum self::TYPE_*}
 * @property Page $page  {1:1 Page::$lesson, isMain=true}
 * @property DateTimeImmutable|null $dateStart
 * @property DateTimeImmutable|null $dateEnd
 * @property DateTimeImmutable $added
 * @property OneHasMany|LessonFile[] $files    {1:m LessonFile::$lesson}
 * @property OneHasMany|HomeworkAssignment[] $homeworkAssignments   {1:m HomeworkAssignment::$lesson}
 * @property OneHasMany|LessonTeamRole[] $teamRoles   {1:m LessonTeamRole::$lesson}
 * @property OneHasMany|Exercise[] $exercises    {1:m Exercise::$lesson, orderBy=orderInLesson}
 */
class Lesson extends Entity
{

	const TYPE_HTML = 'html';
	const TYPE_JS = 'js';
	const TYPE_REACT = 'react';
	const TYPE_UX = 'ux';
	const TYPE_OTHER = 'other';



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
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getDateStart(): ?DateTimeImmutable
	{
		return $this->dateStart;
	}



	/**
	 * @param DateTimeImmutable|null $dateStart
	 */
	public function setDateStart(?DateTimeImmutable $dateStart): void
	{
		$this->dateStart = $dateStart;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getDateEnd(): ?DateTimeImmutable
	{
		return $this->dateEnd;
	}



	/**
	 * @param DateTimeImmutable|null $dateEnd
	 */
	public function setDateEnd(?DateTimeImmutable $dateEnd): void
	{
		$this->dateEnd = $dateEnd;
	}



	/**
	 * @return LessonFile[]|OneHasMany
	 */
	public function getFiles()
	{
		return $this->files;
	}



	/**
	 * @return HomeworkAssignment[]|OneHasMany
	 */
	public function getHomeworkAssignments()
	{
		return $this->homeworkAssignments;
	}



	/**
	 * @return LessonTeamRole[]|OneHasMany
	 */
	public function getTeamRoles()
	{
		return $this->teamRoles;
	}



	public function getTranslatedPageType()
	{
		return self::getTypes()[$this->getType()];
	}



	/**
	 * @return LessonTeamRole[]|ICollection
	 */
	public function getLecturers(): ICollection
	{
		return $this->teamRoles->toCollection()->findBy(["type" => LessonTeamRole::TYPE_LECTURER]);
	}


	public static function getTypes()
	{
		return [
			Lesson::TYPE_HTML => "HTML a CSS",
			Lesson::TYPE_JS => "JavaScript",
			Lesson::TYPE_REACT => "React",
			Lesson::TYPE_UX => "UX",
			Lesson::TYPE_OTHER => "Ostatní",
		];

	}



	/**
	 * @return Exercise[]|OneHasMany
	 */
	public function getExercises()
	{
		return $this->exercises;
	}


	/**
	 * @return Exercise[]|OneHasMany
	 */
	public function getPublishedExercises()
	{
		return $this->exercises->toCollection()->findBy(["published" => TRUE]);
	}


}
