<?php

namespace App\Model\Page\Page;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Page|NULL getById($id)
 */
class PageRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Page::class];
	}



	public function getPageById($id): ?Page
	{
		return $this->getById($id);
	}



	public function getPageListOrdered()
	{
		return $this->findAll()->orderBy("added", "DESC");
	}



	public function getPageListOrderedByAddedForPaginator($from, $limit)
	{
		return $this->findAll()->limitBy($limit, $from)->orderBy("added", "DESC");

	}

}
