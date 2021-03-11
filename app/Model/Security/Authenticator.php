<?php

namespace App\Model\Security;

use App\Model\User\User\UserRepository;
use Nette;
use Nextras\Dbal\Utils\DateTimeImmutable;

class Authenticator implements Nette\Security\IAuthenticator
{

	/** @var UserRepository */
	private $userRepository;



	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}



	/**
	 * Performs an authentication.
	 *
	 * @param array $credentials
	 * @return Nette\Security\IIdentity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Nette\Security\IIdentity
	{
		[$email, $password] = $credentials;
		$row = $this->userRepository->getUserByEmail($email);
		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!$row->verifyPassword($password)) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		$row->setLastLogin(new DateTimeImmutable());
		$this->userRepository->persistAndFlush($row);

		return new Identity($row->getId(), $row->getRoles());
	}

}



class InvalidOldPasswordException extends \Exception
{

}
