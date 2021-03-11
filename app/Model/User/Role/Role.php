<?php

namespace App\Model\User\Role;

use App\Model\User\User\User;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;


/**
 * Role
 *
 * @property int $id    {primary}
 * @property string $name
 * @property string $nameCs
 * @property ManyHasMany|User[] $users  {m:m User::$roles}
 */
class Role extends Entity
{

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
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
	public function getNameCs(): string
	{
		return $this->nameCs;
	}



	/**
	 * @param string $nameCs
	 */
	public function setNameCs(string $nameCs): void
	{
		$this->nameCs = $nameCs;
	}



	/**
	 * @return User[]|ManyHasMany
	 */
	public function getUsers()
	{
		return $this->users;
	}



	/**
	 * @param User[]|ManyHasMany $users
	 */
	public function setUsers($users): void
	{
		$this->users = $users;
	}
}
