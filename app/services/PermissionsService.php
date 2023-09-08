<?php

namespace App\Services;

use App\Models\Permissions;

class PermissionsService extends AbstractService
{
    const ERROR_UNABLE_CREATE_MODULE = 11001;
    const ERROR_MODULE_NOT_FOUND = 11002;
    const ERROR_INCORRECT_MODULE = 11003;
    const ERROR_UNABLE_UPDATE_MODULE= 11004;
    const ERROR_UNABLE_DELETE_MODULE = 1105;

    public function createPermission(array $permissionData)
    {
        try {
            $findPermission = Permissions::findFirst(
                [
                    'conditions' => 'name = :name:',
                    'bind'       => [
                        'name' => $permissionData['name']
                    ]
                ]
            );

            if(!$findPermission){
                $permission   = new Permissions();
                $result = $permission->setName($permissionData['name'])->create();

                if (!$result) {
                    throw new ServiceException('Unable to create permission', self::ERROR_UNABLE_CREATE_MODULE);
                }
            } else {
                throw new ServiceException('Permission already exists', self::ERROR_ALREADY_EXISTS, null);
            }



        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function updatePermission(array $permissionData)
    {
        try {
            $permission = Permissions::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $permissionData['id']
                    ]
                ]
            );


            $permissionData['name'] = (is_null($permissionData['name'])) ? $permission->getEmail() : $permissionData['name'];

            $result = $permission->setName($permissionData['name'])
                ->update();

            if (!$result) {
                throw new ServiceException('Unable to update permission', self::ERROR_UNABLE_UPDATE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deletePermission($permissionId)
    {
        try {
            $permission = Permissions::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $permissionId
                    ]
                ]
            );

            if (!$permission) {
                throw new ServiceException("Permission not found", self::ERROR_MODULE_NOT_FOUND);
            }

            $result = $permission->delete();

            if (!$result) {
                throw new ServiceException('Unable to delete permission', self::ERROR_UNABLE_DELETE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getPermissionsList()
    {
        try {
            $permissions = Permissions::find(
                [
                    'conditions' => '',
                    'bind'       => [],
                    'columns'    => "id, name",
                ]
            );

            if (!$permissions) {
                return [];
            }

            return $permissions->toArray();
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}