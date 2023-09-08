<?php

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\LogsService;

class LogsController extends AbstractController
{

    public function getLogsListAction()
    {
        try {
            $logsList = $this->logsService->getLogsList();
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $logsList;
    }

}