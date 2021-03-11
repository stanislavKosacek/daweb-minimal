<?php

namespace App\Model\Czechitas\HomeworkAssignment;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method HomeworkAssignment|NULL getById($id)
 */
class HomeworkAssignmentRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [HomeworkAssignment::class];
	}



	/**
	 * @param $id
	 * @return HomeworkAssignment|null
	 */
	public function getHomeworkAssignmentById($id): ?HomeworkAssignment
	{
		return $this->getById($id);
	}



	/**
	 * @return HomeworkAssignment[]|ICollection
	 */
	public function findHomeworkAssignmentList(): ICollection
	{
		return $this->findAll()->orderBy("added", "ASC");
	}

}
