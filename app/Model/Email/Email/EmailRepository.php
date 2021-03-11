<?php

namespace App\Model\Email\Email;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Email|NULL getById($id)
 */
class EmailRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Email::class];
	}



	public function getEmailById($id)
	{
		return $this->getById($id);
	}



	public function getEmailListOrdered()
	{
		return $this->findAll()->orderBy("added", "DESC");
	}



	public function getEmailListOrderedByAddedForPaginator($from, $limit)
	{
		return $this->findAll()->limitBy($limit, $from)->orderBy("added", "DESC");

	}



	/**
	 * @return EMail[]|ICollection
	 */
	public function findUnsentEmails(?int $limit = NULL): ICollection
	{
		$qb = $this->findBy(["sent" => NULL]);

		if ($limit) {
			$qb->orderBy("added", "ASC");
			$qb->limitBy($limit);
		}

		return $qb;

	}

}
