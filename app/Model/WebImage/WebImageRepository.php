<?php

namespace App\Model\WebImage;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method WebImage|NULL getById($id)
 */
class WebImageRepository extends Repository
{


	static function getEntityClassNames(): array
	{
		return [WebImage::class];
	}



	public function getWebImageById($id)
	{
		return $this->getById($id);
	}



	/**
	 * @return WebImage[]|ICollection
	 */
	public function findWebImages(): ICollection
	{
		return $this->findAll();
	}

}
