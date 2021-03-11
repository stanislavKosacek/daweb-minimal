<?php

namespace App\Model\Security;

use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\Http\Session;
use Nette\Http\UserStorage as NetteUserStorage;
use Nette\Security\IIdentity;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @author Michael Moravec
 */
class UserStorage extends NetteUserStorage
{

	/** @var UserRepository */
	private $userRepository;



	public function  __construct(Session $sessionHandler, UserRepository $userRepository)
	{
		parent::__construct($sessionHandler);

		$this->userRepository = $userRepository;
	}

	/**
	 * Sets the user identity.
	 * @return UserStorage Provides a fluent interface
	 */
	public function setIdentity(IIdentity $identity = NULL)
	{

		return parent::setIdentity($identity);
	}

	/**
	 * Returns current user identity, if any.
	 * @return IIdentity|NULL
	 */
	public function getIdentity(): ?IIdentity
	{
		$identity = parent::getIdentity();

		if ($identity === NULL) {
			return $identity;
		} elseif ($identity instanceof User) {
			$identity->setLastRequest(new DateTimeImmutable());
			$this->userRepository->persistAndFlush($identity);
			return $identity;
		}

		$user = $this->userRepository->getById($identity->getId());
		if (!$user or $user->isDeleted()) {
			$this->setIdentity(NULL);
			return NULL;
		}
		$this->setLastRequest($user);
		return $user;
	}


	private function setLastRequest(User $user)
	{
		$user->setLastRequest(new DateTimeImmutable());
		$this->userRepository->persistAndFlush($user);
	}
}
