<?php

namespace App\Controllers;

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\PermissionsService;

class PermissionsController extends AbstractController
{

    public function addPermissionAction()
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
            $permissionsList = $this->permissionsService->createPermission($data);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            $logData["response"] = $e->getCode() . " - " . $e->getMessage();
            $this->logsService->createLog($logData);
            throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $permissionsList;
    }

    public function getPermissionListAction()
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "create",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPost()),
        ];

        try {
            $permissionsList = $this->permissionsService->getPermissionsList();
            $logData["response"] = "200 - " . json_encode($permissionsList);
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            $logData["response"] = $e->getCode() . " - " . $e->getMessage();
            $this->logsService->createLog($logData);
            throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $permissionsList;
    }

    public function updatePermissionAction($permissionId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "update",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPut()),
        ];

        $errors = [];
        $data = [];


        $data['name'] = $this->request->getPut('name');
        if ((!is_null($data['name'])) && (!is_string($data['name']))) {
            $errors['name'] = 'String expected';
        }

        if (!ctype_digit($permissionId) || ($permissionId < 0)) {
            $errors['id'] = 'Id must be a positive integer';
        }

        $data['id'] = (int)$permissionId;

        if ($errors) {
            $logData["response"] = "400 - " . json_encode($errors);
            $this->logsService->createLog($logData);
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $this->permissionsService->updatePermission($data);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case PermissionsService::ERROR_UNABLE_UPDATE_MODULE:
                case PermissionsService::ERROR_MODULE_NOT_FOUND:
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

    public function deletePermissionAction($permissionId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "delete",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode(["PermissionId" => $permissionId]),
        ];

        try {
            $this->permissionsService->deletePermission((int)$permissionId);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case PermissionsService::ERROR_UNABLE_DELETE_MODULE:
                case PermissionsService::ERROR_MODULE_NOT_FOUND:
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
{

}