<?php


namespace App\Model\Security;


use Nette\Security\IIdentity;


class Identity implements IIdentity
{

	/** @var int */
	private $id;

	/** @var array */
	private $roles;




	public function __construct(int $id, array $roles)
	{
		$this->id = $id;
		$this->roles = $roles;
	}



	function getId(): int
	{
		return $this->id;
	}



	function getRoles(): array
	{
		return $this->roles;
	}
}
