<?php

$permissionsCollection = new \Phalcon\Mvc\Micro\Collection();
$permissionsCollection->setHandler('\App\Controllers\PermissionsController', true);
$permissionsCollection->setPrefix('/permission');
$permissionsCollection->post('/add', 'addPermissionAction');
$permissionsCollection->get('/list', 'getPermissionListAction');
$permissionsCollection->put('/{id:[1-9][0-9]*}', 'updatePermissionAction');
$permissionsCollection->delete('/{id:[1-9][0-9]*}', 'deletePermissionAction');
$app->mount($permissionsCollection);