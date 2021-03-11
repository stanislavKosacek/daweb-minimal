<?php

namespace App\Model\Language;

use App\Model\User\User\User;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * Language
 * @property int $id    {primary}
 * @property string $name
 * @property string $code
 * @property bool $default
 * @property OneHasMany|User[] $users    {1:m User::$defaultLocale}
 */
class Language extends Entity
{


	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}



	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}



	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}



	/**
	 * @param string $code
	 */
	public function setCode(string $code): void
	{
		$this->code = $code;
	}



	/**
	 * @return bool
	 */
	public function isDefault(): bool
	{
		return $this->default;
	}



	/**
	 * @param bool $default
	 */
	public function setDefault(bool $default): void
	{
		$this->default = $default;
	}


}
