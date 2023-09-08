<?php

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\RulesService;

class RulesController extends AbstractController
{

    public function addRuleAction()
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "create",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPost()),
        ];

        $errors = [];

        $data['modules_id'] = $this->request->getPost('modules_id');
        if ((!empty($data['modules_id'])) && (!is_numeric($data['modules_id']))) {
            $errors['modules_id'] = 'Id expected';
        }

        $data['permissions_id'] = $this->request->getPost('permissions_id');
        if ((!empty($data['permissions_id'])) && (!is_numeric($data['permissions_id']))) {
            $errors['permissions_id'] = 'Id expected';
        }

        $data['profiles_id'] = $this->request->getPost('profiles_id');
        if ((!empty($data['profiles_id'])) && (!is_numeric($data['profiles_id']))) {
            $errors['profiles_id'] = 'Id expected';
        }

        $data['status'] = $this->request->getPost('status');
        if ((!empty($data['status'])) && (!is_numeric($data['status']))) {
            $errors['status'] = 'Id expected';
        }

        if ($errors) {
            $logData["response"] = "400 - " . json_encode($errors);
            $this->logsService->createLog($logData);
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $rulesList = $this->rulesService->createRule($data);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            $logData["response"] = $e->getCode() . " - " . $e->getMessage();
            $this->logsService->createLog($logData);
            throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $rulesList;
    }
    public function getRuleListAction()
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "create",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPost()),
        ];

        try {
            $rulesList = $this->rulesService->getRulesList();
            $logData["response"] = "200 - " . json_encode($rulesList);
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            $logData["response"] = $e->getCode() . " - " . $e->getMessage();
            $this->logsService->createLog($logData);
            throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $rulesList;
    }

    public function updateRuleAction($ruleId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "update",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPut()),
        ];

        $errors = [];
        $data   = [];

        $data['modules_id'] = $this->request->getPut('modules_id');
        if ((!empty($data['modules_id'])) && (!is_numeric($data['modules_id']))) {
            $errors['modules_id'] = 'Id expected';
        }

        $data['permissions_id'] = $this->request->getPut('permissions_id');
        if ((!empty($data['permissions_id'])) && (!is_numeric($data['permissions_id']))) {
            $errors['permissions_id'] = 'Id expected';
        }

        $data['profiles_id'] = $this->request->getPut('profiles_id');
        if ((!empty($data['profiles_id'])) && (!is_numeric($data['profiles_id']))) {
            $errors['profiles_id'] = 'Id expected';
        }

        $data['status'] = $this->request->getPut('status');
        if ((!empty($data['status'])) && (!is_numeric($data['status']))) {
            $errors['status'] = 'Id expected';
        }

        if (!ctype_digit($ruleId) || ($ruleId < 0)) {
            $errors['id'] = 'Id must be a positive integer';
        }

        $data['id'] = (int)$ruleId;

        if ($errors) {
            $logData["response"] = "400 - " . json_encode($errors);
            $this->logsService->createLog($logData);
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $this->rulesService->updateRule($data);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case RulesService::ERROR_UNABLE_UPDATE_MODULE:
                case RulesService::ERROR_MODULE_NOT_FOUND:
                    $logData["response"] = $e->getCode() . " - " . $e->getMessage();
                    $this->logsService->createLog($logData);
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    $logData["response"] = $e->getCode() . " - " . $e->getMessage();
                    $this->logsService->createLog($logData);
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
    }

    public function deleteRuleAction($ruleId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "delete",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode(["RuleId" => $ruleId]),
        ];

        try {
            $this->rulesService->deleteRule((int)$ruleId);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case RulesService::ERROR_UNABLE_DELETE_MODULE:
                case RulesService::ERROR_MODULE_NOT_FOUND:
                    $logData["response"] = $e->getCode() . " - " . $e->getMessage();
                    $this->logsService->createLog($logData);
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    $logData["response"] = $e->getCode() . " - " . $e->getMessage();
                    $this->logsService->createLog($logData);
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
    }
}