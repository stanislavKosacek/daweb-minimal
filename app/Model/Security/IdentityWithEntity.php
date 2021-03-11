<?php


namespace App\Model\Security;


use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\Security\IIdentity;
use Nextras\Dbal\Utils\DateTimeImmutable;


interface IdentityWithEntityFactory {

    public function create(int $id): IdentityWithEntity;

}

class IdentityWithEntity implements IIdentity
{

	/** @var int */
	private $id;

	/** @var array */
	private $roles;

	/** @var User */
	private $entity;

	/** @var UserRepository */
	private $userRepository;



	public function __construct(int $id, UserRepository $userRepository)
	{
		$this->id = $id;
		$this->userRepository = $userRepository;
		$this->entity = $userRepository->getById($id);
		if ($this->entity) {
			$this->roles = $this->entity->getRoles();
		}
	}



	function getId(): int
	{
		return $this->id;
	}



	function getRoles(): array
	{
		return $this->roles;
	}



	/**
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->name;
	}



	/**
	 * @return string|null
	 */
	public function getSurname(): ?string
	{
		return $this->surname;
	}



	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}



	/**
	 * @return string|null
	 */
	public function getImage(): ?string
	{
		return $this->image;
	}



	/**
	 * @return string|null
	 */
	public function getUsername(): ?string
	{
		return $this->username;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}
}
