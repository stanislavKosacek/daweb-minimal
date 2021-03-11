<?php

namespace App\Model\Language;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Language|NULL getById($id)
 */
class LanguageRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Language::class];
	}



	public function getLanguageById($id)
	{
		return $this->getById($id);
	}



	public function getLanguageByCode($code): ?Language
	{
		return $this->getBy(["code" => $code]);
	}



	/**
	 * @return Language[]|ICollection
	 */
	public function findLanguageList()
	{
		return $this->findAll();
	}



	/**
	 * @return Language|null
	 */
	public function getDefaultLocale(): ?Language
	{
		return $this->getBy(["default" => TRUE]);
	}

}
