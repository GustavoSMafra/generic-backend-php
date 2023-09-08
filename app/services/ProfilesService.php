<?php

namespace App\Services;

use App\Models\Profiles;

class ProfilesService extends AbstractService
{
    const ERROR_UNABLE_CREATE_MODULE = 11001;
    const ERROR_MODULE_NOT_FOUND = 11002;
    const ERROR_INCORRECT_MODULE = 11003;
    const ERROR_UNABLE_UPDATE_MODULE= 11004;
    const ERROR_UNABLE_DELETE_MODULE = 1105;

    public function createProfile(array $profileData)
    {
        try {
            $findProfile = Profiles::findFirst(
                [
                    'conditions' => 'name = :name:',
                    'bind'       => [
                        'name' => $profileData['name']
                    ]
                ]
            );

            if(!$findProfile){
                $profile   = new Profiles();
                $result = $profile->setName($profileData['name'])->create();

                if (!$result) {
                    throw new ServiceException('Unable to create profile', self::ERROR_UNABLE_CREATE_MODULE);
                }
            } else {
                throw new ServiceException('Profile already exists', self::ERROR_ALREADY_EXISTS, null);
            }



        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function updateProfile(array $profileData)
    {
        try {
            $profile = Profiles::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $profileData['id']
                    ]
                ]
            );


            $profileData['name'] = (is_null($profileData['name'])) ? $profile->getEmail() : $profileData['name'];

            $result = $profile->setName($profileData['name'])
                ->update();

            if (!$result) {
                throw new ServiceException('Unable to update profile', self::ERROR_UNABLE_UPDATE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteProfile($profileId)
    {
        try {
            $profile = Profiles::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $profileId
                    ]
                ]
            );

            if (!$profile) {
                throw new ServiceException("Profile not found", self::ERROR_MODULE_NOT_FOUND);
            }

            $result = $profile->delete();

            if (!$result) {
                throw new ServiceException('Unable to delete profile', self::ERROR_UNABLE_DELETE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getProfilesList()
    {
        try {
            $profiles = Profiles::find(
                [
                    'conditions' => '',
                    'bind'       => [],
                    'columns'    => "id, name",
                ]
            );

            if (!$profiles) {
                return [];
            }

            return $profiles->toArray();
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}