<?php

namespace App\Model\Czechitas\Exercise;

use App\Model\Czechitas\ExerciseSolutionFile\ExerciseSolutionFile;
use App\Model\Czechitas\Lesson\Lesson;
use Nette\Utils\Strings;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * Exercise
 *
 * @property int $id    {primary}
 * @property string $name
 * @property string|null $assignment
 * @property ManyHasOne|Lesson|null $lesson {m:1 Lesson::$exercises}
 * @property int|null $orderInLesson
 * @property OneHasMany|ExerciseSolutionFile[] $exerciseSolutionFiles    {1:m ExerciseSolutionFile::$exercise}
 * @property bool $published
 * @property bool $publishedSolution
 */
class Exercise extends Entity
{


	public function onCreate(): void
	{
		parent::onCreate();
		$this->published = FALSE;
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
	 * @return Lesson|ManyHasOne
	 */
	public function getLesson()
	{
		return $this->lesson;
	}



	/**
	 * @param Lesson|ManyHasOne $lesson
	 */
	public function setLesson(?Lesson $lesson): void
	{
		$this->lesson = $lesson;
	}



	/**
	 * @return string|null
	 */
	public function getAssignment(): ?string
	{
		return $this->assignment;
	}



	/**
	 * @param string|null $assignment
	 */
	public function setAssignment(?string $assignment): void
	{
		$this->assignment = $assignment;
	}



	/**
	 * @return ExerciseSolutionFile[]|OneHasMany
	 */
	public function getExerciseSolutionFiles()
	{
		return $this->exerciseSolutionFiles;
	}



	/**
	 * @return bool
	 */
	public function isPublished(): bool
	{
		return $this->published;
	}



	/**
	 * @param bool $published
	 */
	public function setPublished(bool $published): void
	{
		$this->published = $published;
	}



	/**
	 * @return bool
	 */
	public function isPublishedSolution(): bool
	{
		return $this->publishedSolution;
	}



	/**
	 * @param bool $publishedSolution
	 */
	public function setPublishedSolution(bool $publishedSolution): void
	{
		$this->publishedSolution = $publishedSolution;
	}



	/**
	 * @return int|null
	 */
	public function getOrderInLesson(): ?int
	{
		return $this->orderInLesson;
	}



	/**
	 * @param int|null $orderInLesson
	 */
	public function setOrderInLesson(?int $orderInLesson): void
	{
		$this->orderInLesson = $orderInLesson;
	}



	public function getWebalizeName()
	{
		return Strings::webalize($this->getName());
	}
}
