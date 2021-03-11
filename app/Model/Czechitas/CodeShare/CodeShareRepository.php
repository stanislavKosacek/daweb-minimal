<?php

namespace App\Model\Czechitas\CodeShare;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method CodeShare|NULL getById($id)
 */
class CodeShareRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [CodeShare::class];
	}



	/**
	 * @param $id
	 * @return CodeShare|null
	 */
	public function getCodeShareById($id): ?CodeShare
	{
		return $this->getById($id);
	}



	/**
	 * @return CodeShare[]|ICollection
	 */
	public function getCodeShareList(): ICollection
	{
		return $this->findAll()->orderBy("added", "DESC");
	}



	public function getCodeShareByName(string $name): ?CodeShare
	{
		return $this->getBy(["name" => $name]);
	}

}
