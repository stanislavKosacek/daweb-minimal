<?php

namespace App\Model\Czechitas\LessonTeamRole;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method LessonTeamRole|NULL getById($id)
 */
class LessonTeamRoleRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [LessonTeamRole::class];
	}



	/**
	 * @param $id
	 * @return LessonTeamRole|null
	 */
	public function getLessonTeamRoleById($id): ?LessonTeamRole
	{
		return $this->getById($id);
	}



	/**
	 * @return LessonTeamRole[]|ICollection
	 */
	public function getLessonTeamRoleList(): ICollection
	{
		return $this->findAll();
	}

}
