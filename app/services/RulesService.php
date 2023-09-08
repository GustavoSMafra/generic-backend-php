<?php

namespace App\Services;

use App\Models\Rules;

class RulesService extends AbstractService
{
    const ERROR_UNABLE_CREATE_MODULE = 11001;
    const ERROR_MODULE_NOT_FOUND = 11002;
    const ERROR_INCORRECT_MODULE = 11003;
    const ERROR_UNABLE_UPDATE_MODULE= 11004;
    const ERROR_UNABLE_DELETE_MODULE = 1105;

    public function createRule(array $ruleData)
    {
        try {
            $findRule = Rules::findFirst(
                [
                    'conditions' => 'modules_id = :modules_id: AND permissions_id = :permissions_id: AND profiles_id = :profiles_id:',
                    'bind'       => [
                        'modules_id' => $ruleData['modules_id'],
                        'permissions_id' => $ruleData['permissions_id'],
                        'profiles_id' => $ruleData['profiles_id']
                    ]
                ]
            );

            if(!$findRule){
                $rule   = new Rules();
                $result = $rule->setModulesId($ruleData['modules_id'])
                    ->setPermissionsId($ruleData['permissions_id'])
                    ->setProfilesId($ruleData['profiles_id'])
                    ->setStatus($ruleData['status'])
                    ->create();

                if (!$result) {
                    throw new ServiceException('Unable to create rule', self::ERROR_UNABLE_CREATE_MODULE);
                }
            } else {
                throw new ServiceException('Rule already exists', self::ERROR_ALREADY_EXISTS, null);
            }



        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function updateRule(array $ruleData)
    {
        try {
            $rule = Rules::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $ruleData['id']
                    ]
                ]
            );

            $ruleData['modules_id'] = (is_null($ruleData['modules_id'])) ? $rule->getModulesId() : $ruleData['modules_id'];
            $ruleData['permissions_id'] = (is_null($ruleData['permissions_id'])) ? $rule->getPermissionsId() : $ruleData['permissions_id'];
            $ruleData['profiles_id'] = (is_null($ruleData['profiles_id'])) ? $rule->getProfilesId() : $ruleData['profiles_id'];
            $ruleData['status'] = (is_null($ruleData['status'])) ? $rule->getStatus() : $ruleData['status'];

            $result = $rule->setModulesId($ruleData['modules_id'])
                ->setPermissionsId($ruleData['permissions_id'])
                ->setProfilesId($ruleData['profiles_id'])
                ->setStatus($ruleData['status'])
                ->update();

            if (!$result) {
                throw new ServiceException('Unable to update rule', self::ERROR_UNABLE_UPDATE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteRule($ruleId)
    {
        try {
            $rule = Rules::findFirst(
                [
                    'conditions' => 'id = :id:',
                    'bind'       => [
                        'id' => $ruleId
                    ]
                ]
            );

            if (!$rule) {
                throw new ServiceException("Rule not found", self::ERROR_MODULE_NOT_FOUND);
            }

            $result = $rule->delete();

            if (!$result) {
                throw new ServiceException('Unable to delete rule', self::ERROR_UNABLE_DELETE_MODULE);
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getRulesList()
    {
        try {
            $rules = Rules::find(
                [
                    'conditions' => '',
                    'bind'       => [],
                    'columns'    => "id, modules_id, permissions_id, profiles_id, status",
                ]
            );

            if (!$rules) {
                return [];
            }

            return $rules->toArray();
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}