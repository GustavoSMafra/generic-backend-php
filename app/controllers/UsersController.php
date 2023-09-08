<?php

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\UsersService;

class UsersController extends AbstractController
{

    public function addAction()
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "create",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPost()),
        ];

        $errors = [];
        $data['email'] = $this->request->getPost('email');
        if (!is_string($data['email']) || !preg_match('/^[A-Za-z0-9._%+-]{3,100}@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $data['email'])) {
            $errors['email'] = 'Invalid email';
        }

        $data['password'] = $this->request->getPost('password');
        if (!is_string($data['password']) || !preg_match('/^[A-z0-9_-]{6,18}$/', $data['password'])) {
            $errors['password'] = 'Invalid password';
        }

        $data['first_name'] = $this->request->getPost('first_name');
        if ((!empty($data['first_name'])) && (!is_string($data['first_name']))) {
            $errors['first_name'] = 'String expected';
        }

        $data['last_name'] = $this->request->getPost('last_name');
        if ((!empty($data['last_name'])) && (!is_string($data['last_name']))) {
            $errors['last_name'] = 'String expected';
        }

        $data['profile_id'] = $this->request->getPost('profile_id') ?? 2;

        if ($errors) {
            $logData["response"] = "400 - " . json_encode($errors);
            $this->logsService->createLog($logData);
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $this->usersService->createUser($data);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_ALREADY_EXISTS:
                case UsersService::ERROR_UNABLE_CREATE_USER:
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

    public function getUserListAction()
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "list",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => ""
        ];
        try {
            $userList = $this->usersService->getUserList();
            $logData["response"] = "200 - " . json_encode($userList);
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            $logData["response"] = $e->getCode() . " - " . $e->getMessage();
            $this->logsService->createLog($logData);
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $userList;
    }

    public function updateUserAction($userId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "update",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode($this->request->getPut()),
        ];

        $errors = [];
        $data   = [];

        $data['email'] = $this->request->getPut('email');
        if ((!is_null($data['email'])) && (!is_string($data['email']) || !preg_match(
                    '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
              $data['email']
            ))
        ) {
            $errors['email'] = 'Email must consist of 3-16 latin symbols, numbers or \'-\' and \'_\' symbols';
        }

        $data['password'] = $this->request->getPut('password');
        if ((!is_null($data['password'])) && (!is_string($data['password']) || !preg_match(
                    '/^[A-z0-9_-]{6,18}$/',
              $data['password']
            ))
        ) {
            $errors['password'] = 'Password must consist of 6-18 latin symbols, numbers or \'-\' and \'_\' symbols';
        }

        $data['old_password'] = $this->request->getPut('old_password');
        if ((!is_null($data['old_password'])) && (!is_string($data['old_password']))) {
            $errors['old_password'] = 'Old password must be a string';
        }

        $data['first_name'] = $this->request->getPut('first_name');
        if ((!is_null($data['first_name'])) && (!is_string($data['first_name']))) {
            $errors['first_name'] = 'String expected';
        }

        $data['last_name'] = $this->request->getPut('last_name');
        if ((!is_null($data['last_name'])) && (!is_string($data['last_name']))) {
            $errors['last_name'] = 'String expected';
        }

        if (!ctype_digit($userId) || ($userId < 0)) {
            $errors['id'] = 'Id must be a positive integer';
        }

        $data['id'] = (int)$userId;
        $data['profile_id'] = $data['profile_id'] ?? null;

        if ($errors) {
            $logData["response"] = "400 - " . json_encode($errors);
            $this->logsService->createLog($logData);
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        try {
            $this->usersService->updateUser($data);
            if(!is_null($data['old_password'])){
                $logData["response"] = "204 - Change password";
            } else {
                $logData["response"] = "204 - Change only data";
            }
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case UsersService::ERROR_UNABLE_UPDATE_USER:
                case UsersService::ERROR_USER_NOT_FOUND:
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

    public function deleteUserAction($userId)
    {
        $logData = [
            "ip" => $_SERVER['REMOTE_ADDR'],
            "action" => "delete",
            "url" => $_SERVER['REQUEST_URI'],
            "params" => json_encode(["userId" => $userId]),
        ];

        try {
            $this->usersService->deleteUser((int)$userId);
            $logData["response"] = "204 - ";
            $this->logsService->createLog($logData);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case UsersService::ERROR_UNABLE_DELETE_USER:
                case UsersService::ERROR_USER_NOT_FOUND:
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
