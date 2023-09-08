<?php

namespace App\Services;

use App\Models\Logs;
class LogsService
{

    const ERROR_UNABLE_CREATE_LOG = 11001;

    public function createLog(array $logData)
    {
        $log   = new Logs();
        $result = $log->setIp($logData['ip'])
            ->setAction($logData['action'])
            ->setUrl($logData['url'])
            ->setParams($logData['params'])
            ->setResponse($logData['response'])
            ->create();

        if (!$result) {
            throw new ServiceException('Unable to create log', self::ERROR_UNABLE_CREATE_LOG);
        }
    }

    public function getLogsList()
    {
        try {
            $logs = Logs::find(
                [
                    'conditions' => '',
                    'bind'       => [],
                    'columns'    => "id, ip, action, url, params, response",
                ]
            );

            if (!$logs) {
                return [];
            }

            return $logs->toArray();
        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}