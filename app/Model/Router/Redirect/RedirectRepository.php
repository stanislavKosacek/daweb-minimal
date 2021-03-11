<?php

namespace App\Model\Router\Redirect;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Redirect|NULL getById($id)
 */
class RedirectRepository extends Repository
{


	static function getEntityClassNames(): array
	{
		return [Redirect::class];

	}



	public function getRedirectById($id): ?Redirect
	{
		return $this->getById($id);
	}



	/**
	 * @return Redirect[]|ICollection
	 */
	public function findRedirects(): ICollection
	{
		return $this->findAll();
	}



	/**
	 * @param string $relativeUrl
	 * @return Redirect|null
	 */
	public function getRedirectByFrom(string $relativeUrl): ?Redirect
	{
		return $this->getBy(["from" => $relativeUrl]);
	}



	public function getRedirectByTo(string $url): ?Redirect
	{
		return $this->getBy(["to" => $url]);
	}
}
