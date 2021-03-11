<?php

namespace App\Model\User\User;

use Nextras\Orm\Repository\Repository;


/**
 * @method User|NULL getById($id)
 */
class UserRepository extends Repository
{




	static function getEntityClassNames(): array
	{
		return [User::class];
	}



	public function getUserById($id)
	{
		return $this->getById($id);
	}



	public function findUsersOrderBySurname()
	{
		return $this->findAll()->orderBy("surname", "ASC");
	}



	/**
	 * @param string $email
	 * @return User|null
	 */
	public function getUserByEmail(string $email): ?User
	{
		return $this->getBy(["email" => $email]);
	}



	/**
	 * @param string $forgottenPasswordHash
	 * @return User|null
	 */
	public function getUserByForgottenPasswordHash(string $forgottenPasswordHash): ?User
	{
		return $this->getBy(["forgottenPasswordHash" => $forgottenPasswordHash]);
	}



	public function emailExist(string $email)
	{
		if ($this->findBy(["email" => $email])->count() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}



	public function usernameExist(string $username)
	{
		if ($this->findBy(["username" => $username])->count() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}



	/**
	 * @return array|User[]
	 */
	public function findAdmins(): array
	{
		$users = $this->findAll()->orderBy("surname")->fetchAll();
		return array_filter($users, function (User $user) {
			if (in_array("admin", $user->getRoles())) {
				return TRUE;
			}
		});
	}


	public function findUsersWithoutRole(): array
	{
		$users = $this->findAll()->fetchAll();
		return array_filter($users, function (User $user) {
			if (empty($user->getRoles())) {
				return TRUE;
			}
		});
	}



	public function findUsersByRole(string $role)
	{
		$users = $this->findAll()->fetchAll();
		return array_filter($users, function (User $user) use ($role) {
			if (in_array($role, $user->getRoles())) {
				return TRUE;
			}
		});
	}

}
