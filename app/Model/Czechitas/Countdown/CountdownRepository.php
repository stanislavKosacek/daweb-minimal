<?php

namespace App\Model\Czechitas\Countdown;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Countdown|NULL getById($id)
 */
class CountdownRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Countdown::class];
	}



	/**
	 * @param $id
	 * @return Countdown|null
	 */
	public function getCountdownById($id): ?Countdown
	{
		return $this->getById($id);
	}



	/**
	 * @return Countdown[]|ICollection
	 */
	public function getCountdownList(): ICollection
	{
		return $this->findAll();
	}



	/**
	 * @return Countdown|null
	 */
	public function getDefaultCountdown(): ?Countdown
	{
		return $this->getBy(["default" => TRUE]);
	}
}
