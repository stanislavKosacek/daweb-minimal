<?php

namespace App\Model\Czechitas\ExerciseSolutionFile;

use App\Model\Czechitas\Exercise\Exercise;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;


/**
 * ExerciseSolutionFile
 *
 * @property int $id    {primary}
 * @property string|null $filename
 * @property string|null $code
 * @property string|null $language
 * @property ManyHasOne|Exercise $exercise {m:1 Exercise::$exerciseSolutionFiles}
 */
class ExerciseSolutionFile extends Entity
{


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
	public function getFilename(): ?string
	{
		return $this->filename;
	}



	/**
	 * @param string|null $filename
	 */
	public function setFilename(?string $filename): void
	{
		$this->filename = $filename;
	}



	/**
	 * @return string|null
	 */
	public function getCode(): ?string
	{
		return $this->code;
	}



	/**
	 * @param string|null $code
	 */
	public function setCode(?string $code): void
	{
		$this->code = $code;
	}



	/**
	 * @return Exercise|ManyHasOne
	 */
	public function getExercise()
	{
		return $this->exercise;
	}



	/**
	 * @param Exercise|ManyHasOne $exercise
	 */
	public function setExercise($exercise): void
	{
		$this->exercise = $exercise;
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
}
