<?php

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\ModulesService;

class ModulesController extends AbstractController
{

    public function addModuleAction()
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "create",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPost()),
        ];

        $errors = [];

        $data['name'] = $this->request->getPost('name');
        if ((!empty($data['name'])) && (!is_string($data['name']))) {
            $errors['name'] = 'String expected';
        }

        if ($errors) {
            $logData["response"] = "400 - " . json_encode($errors);
            $this->logsService->createLog($logData);
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $modulesList = $this->modulesService->createModule($data);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            $logData["response"] = $e->getCode() . " - " . $e->getMessage();
            $this->logsService->createLog($logData);
            throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $modulesList;
    }
    public function getModuleListAction()
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "create",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPost()),
        ];

        try {
            $modulesList = $this->modulesService->getModulesList();
            $logData["response"] = "200 - " . json_encode($modulesList);
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            $logData["response"] = $e->getCode() . " - " . $e->getMessage();
            $this->logsService->createLog($logData);
            throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $modulesList;
    }

    public function updateModuleAction($moduleId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "update",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPut()),
        ];

        $errors = [];
        $data   = [];


        $data['name'] = $this->request->getPut('name');
        if ((!is_null($data['name'])) && (!is_string($data['name']))) {
            $errors['name'] = 'String expected';
        }

        if (!ctype_digit($moduleId) || ($moduleId < 0)) {
            $errors['id'] = 'Id must be a positive integer';
        }

        $data['id'] = (int)$moduleId;

        if ($errors) {
            $logData["response"] = "400 - " . json_encode($errors);
            $this->logsService->createLog($logData);
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $this->modulesService->updateModule($data);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case ModulesService::ERROR_UNABLE_UPDATE_MODULE:
                case ModulesService::ERROR_MODULE_NOT_FOUND:
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

    public function deleteModuleAction($moduleId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "delete",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode(["ModuleId" => $moduleId]),
        ];

        try {
            $this->modulesService->deleteModule((int)$moduleId);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case ModulesService::ERROR_UNABLE_DELETE_MODULE:
                case ModulesService::ERROR_MODULE_NOT_FOUND:
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