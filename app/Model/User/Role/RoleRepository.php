<?php

namespace App\Model\User\Role;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Role|NULL getById($id)
 */
class RoleRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Role::class];
	}



	public function getRoleById($id)
	{
		return $this->getById($id);
	}



	/**
	 * @return Role[]|ICollection
	 */
	public function findRoleList(): ICollection
	{
		return $this->findAll();
	}



	public function getRoleByName(string $name)
	{
		return $this->getBy(["name" => $name]);
	}
}
