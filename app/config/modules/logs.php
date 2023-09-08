<?php

$logsCollection = new \Phalcon\Mvc\Micro\Collection();
$logsCollection->setHandler('\App\Controllers\LogsController', true);
$logsCollection->setPrefix('/logs');
$logsCollection->get('/list', 'getLogsListAction');

$app->mount($logsCollection);