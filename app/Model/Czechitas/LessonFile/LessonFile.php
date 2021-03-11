<?php

namespace App\Model\Czechitas\LessonFile;

use App\Model\Czechitas\Lesson\Lesson;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;


/**
 * LessonFile
 *
 * @property int $id    {primary}
 * @property string $filename
 * @property string|null $name
 * @property string|null $description
 * @property DateTimeImmutable $added
 * @property Lesson $lesson {m:1 Lesson::$files}
 */
class LessonFile extends Entity
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
	 * @return string
	 */
	public function getFilename(): string
	{
		return $this->filename;
	}



	/**
	 * @param string $filename
	 */
	public function setFilename(string $filename): void
	{
		$this->filename = $filename;
	}



	/**
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->name;
	}



	/**
	 * @param string|null $name
	 */
	public function setName(?string $name): void
	{
		$this->name = $name;
	}



	/**
	 * @return string|null
	 */
	public function getDescription(): ?string
	{
		return $this->description;
	}



	/**
	 * @param string|null $description
	 */
	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}



	/**
	 * @return Lesson
	 */
	public function getLesson(): Lesson
	{
		return $this->lesson;
	}



	/**
	 * @param Lesson $lesson
	 */
	public function setLesson(Lesson $lesson): void
	{
		$this->lesson = $lesson;
	}


	public function getNameWithPath()
	{
		return "assets/upload/lesson-files/" . $this->getFilename();

	}
}
