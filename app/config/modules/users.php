<?php

$usersCollection = new \Phalcon\Mvc\Micro\Collection();
$usersCollection->setHandler('\App\Controllers\UsersController', true);
$usersCollection->setPrefix('/user');
$usersCollection->post('/add', 'addAction');
$usersCollection->get('/list', 'getUserListAction');
$usersCollection->put('/{id:[1-9][0-9]*}', 'updateUserAction');
$usersCollection->delete('/{id:[1-9][0-9]*}', 'deleteUserAction');
$app->mount($usersCollection);