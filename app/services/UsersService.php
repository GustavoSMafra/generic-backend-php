<?php

namespace App\Services;

use App\Models\Users;

class UsersService extends AbstractService
{

	const ERROR_UNABLE_CREATE_USER = 11001;
	const ERROR_USER_NOT_FOUND = 11002;
	const ERROR_INCORRECT_USER = 11003;
	const ERROR_UNABLE_UPDATE_USER = 11004;
	const ERROR_UNABLE_DELETE_USER = 1105;

	public function createUser(array $userData)
	{
		 try {
             $findUser = Users::findFirst(
                 [
                     'conditions' => 'email = :email:',
                     'bind'       => [
                         'email' => $userData['email']
                     ]
                 ]
             );

             if(!$findUser){
                 $user   = new Users();
                 $profile_id = $userData['profile_id'] ?? 2;
                 $result = $user->setEmail($userData['email'])
                     ->setPassword(password_hash($userData['password'], PASSWORD_DEFAULT))
                     ->setFirstName($userData['first_name'])
                     ->setLastName($userData['last_name'])
                     ->setProfileId($userData['profile_id'])
                     ->create();

                 if (!$result) {
                     throw new ServiceException('Unable to create user', self::ERROR_UNABLE_CREATE_USER);
                 }
             } else {
                 throw new ServiceException('User already exists', self::ERROR_ALREADY_EXISTS, null);
             }



		 } catch (\PDOException $e) {
		 		throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		 }
	}

	public function updateUser(array $userData)
	{
		try {
			$user = Users::findFirst(
				[
					'conditions' => 'id = :id:',
					'bind'       => [
						'id' => $userData['id']
					]
				]
			);

            if(!is_null($userData['old_password']) && !is_null($userData['password'])){
                if(!password_verify($userData['old_password'], $user->getPassword())){
                    throw new ServiceException('Wrong old password', self::ERROR_UNABLE_UPDATE_USER);
                } else {
                    $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                }
            } else {
                $userData['password'] = $user->getPassword();
            }

			$userData['email']      = (is_null($userData['email'])) ? $user->getEmail() : $userData['email'];
			$userData['first_name'] = (is_null($userData['first_name'])) ? $user->getFirstName() : $userData['first_name'];
			$userData['last_name']  = (is_null($userData['last_name'])) ? $user->getLastName() : $userData['last_name'];
            $userData['profile_id'] = (is_null($userData['profile_id'])) ? $user->getProfileId() : $userData['profile_id'];

			$result = $user->setEmail($userData['email'])
			               ->setPassword($userData['password'])
			               ->setFirstName($userData['first_name'])
			               ->setLastName($userData['last_name'])
                           ->setProfileId($userData['profile_id'])
			               ->update();

			if (!$result) {
				throw new ServiceException('Unable to update user', self::ERROR_UNABLE_UPDATE_USER);
			}

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function deleteUser($userId)
	{
		try {
			$user = Users::findFirst(
				[
					'conditions' => 'id = :id:',
					'bind'       => [
						'id' => $userId
					]
				]
			);

			if (!$user) {
				throw new ServiceException("User not found", self::ERROR_USER_NOT_FOUND);
			}

			$result = $user->delete();

			if (!$result) {
				throw new ServiceException('Unable to delete user', self::ERROR_UNABLE_DELETE_USER);
			}

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function getUserList()
	{
		try {
			$users = Users::find(
				[
					'conditions' => '',
					'bind'       => [],
					'columns'    => "id, email, first_name, last_name, profile_id",
				]
			);

			if (!$users) {
				return [];
			}

			return $users->toArray();
		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}
}
