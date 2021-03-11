<?php

namespace App\Model\Czechitas\HomeworkSolution;

use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\User\User\User;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method HomeworkSolution|NULL getById($id)
 */
class HomeworkSolutionRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [HomeworkSolution::class];
	}



	/**
	 * @param $id
	 * @return HomeworkSolution|null
	 */
	public function getHomeworkSolutionById($id): ?HomeworkSolution
	{
		return $this->getById($id);
	}



	/**
	 * @return HomeworkSolution[]|ICollection
	 */
	public function getHomeworkSolutionList(): ICollection
	{
		return $this->findAll();
	}



	/**
	 * @param User $user
	 * @param HomeworkAssignment $homeworkAssignment
	 * @return HomeworkSolution|null
	 */
	public function getByUserAndHomeworkAssignment(User $user, HomeworkAssignment $homeworkAssignment): ?HomeworkSolution
	{
		return $this->getBy(["user" => $user, "homeworkAssignment" => $homeworkAssignment]);
	}



	/**
	 * @return HomeworkSolution[]|ICollection
	 */
	public function findUncheckedHomeworkSolutions(): ICollection
	{
		return $this->findBy(["checked" => NULL])->orderBy("added", "DESC");
	}

}
