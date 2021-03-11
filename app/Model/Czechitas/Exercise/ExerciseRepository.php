<?php

namespace App\Model\Czechitas\Exercise;

use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Exercise|NULL getById($id)
 */
class ExerciseRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Exercise::class];
	}



	/**
	 * @param $id
	 * @return Exercise|null
	 */
	public function getExerciseById($id): ?Exercise
	{
		return $this->getById($id);
	}



	/**
	 * @return Exercise[]|ICollection
	 */
	public function getExerciseList(): ICollection
	{
		return $this->findAll();
	}



	/**
	 * @return Exercise[]|ICollection
	 */
	public function findPublished(): ICollection
	{
		return $this->findBy(["lesson!=" => NULL, "lesson->page->published<" => new DateTimeImmutable(), "published" => TRUE])->orderBy(["lesson->dateStart" => "ASC", "orderInLesson" => "ASC"]);
	}

}
