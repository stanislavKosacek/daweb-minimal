<?php

namespace App\Model\Czechitas\ExerciseSolutionFile;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method ExerciseSolutionFile|NULL getById($id)
 */
class ExerciseSolutionFileRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [ExerciseSolutionFile::class];
	}



	/**
	 * @param $id
	 * @return ExerciseSolutionFile|null
	 */
	public function getExerciseSolutionFileById($id): ?ExerciseSolutionFile
	{
		return $this->getById($id);
	}



	/**
	 * @return ExerciseSolutionFile[]|ICollection
	 */
	public function getExerciseSolutionFileList(): ICollection
	{
		return $this->findAll();
	}

}
