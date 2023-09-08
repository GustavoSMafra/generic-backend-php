<?php

$modulesCollection = new \Phalcon\Mvc\Micro\Collection();
$modulesCollection->setHandler('\App\Controllers\ModulesController', true);
$modulesCollection->setPrefix('/module');
$modulesCollection->post('/add', 'addModuleAction');
$modulesCollection->get('/list', 'getModuleListAction');
$modulesCollection->put('/{id:[1-9][0-9]*}', 'updateModuleAction');
$modulesCollection->delete('/{id:[1-9][0-9]*}', 'deleteModuleAction');
$app->mount($modulesCollection);