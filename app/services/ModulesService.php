<?php

namespace App\Services;

use App\Models\Modules;

class ModulesService extends AbstractService
{
    const ERROR_UNABLE_CREATE_MODULE = 11001;
    const ERROR_MODULE_NOT_FOUND = 11002;
    const ERROR_INCORRECT_MODULE = 11003;
    const ERROR_UNABLE_UPDATE_MODULE= 11004;
    const ERROR_UNABLE_DELETE_MODULE = 1105;

    public function createModule(array $moduleData)
    {
        try {
            $findModule = Modules::findFirst(
                [
                    'conditions' => 'name = :name:',
                    'bind'       => [
                        'name' => $moduleData['name']
                    ]
                ]
            );

            if(!$findModule){
                $module   = new Modules();
                $result = $module->setName($moduleData['name'])->create();

                if (!$result) {
                    throw new ServiceException('Unable to create module', self::ERROR_UNABLE_CREATE_MODULE);
                }
            } else {
                throw new ServiceException('Module already exists', self::ERROR_ALREADY_EXISTS, null);
            }



        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function updateModule(array $moduleData)
    {
        try {
            $module = Modules::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $moduleData['id']
                    ]
                ]
            );


            $moduleData['name'] = (is_null($moduleData['name'])) ? $module->getEmail() : $moduleData['name'];

            $result = $module->setName($moduleData['name'])
                ->update();

            if (!$result) {
                throw new ServiceException('Unable to update module', self::ERROR_UNABLE_UPDATE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteModule($moduleId)
    {
        try {
            $module = Modules::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $moduleId
                    ]
                ]
            );

            if (!$module) {
                throw new ServiceException("Module not found", self::ERROR_MODULE_NOT_FOUND);
            }

            $result = $module->delete();

            if (!$result) {
                throw new ServiceException('Unable to delete module', self::ERROR_UNABLE_DELETE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getModulesList()
    {
        try {
            $modules = Modules::find(
                [
                    'conditions' => '',
                    'bind'       => [],
                    'columns'    => "id, name",
                ]
            );

            if (!$modules) {
                return [];
            }

            return $modules->toArray();
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}