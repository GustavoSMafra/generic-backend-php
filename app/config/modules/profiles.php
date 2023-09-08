<?php

$profilesCollection = new \Phalcon\Mvc\Micro\Collection();
$profilesCollection->setHandler('\App\Controllers\ProfilesController', true);
$profilesCollection->setPrefix('/profile');
$profilesCollection->post('/add', 'addProfileAction');
$profilesCollection->get('/list', 'getProfileListAction');
$profilesCollection->put('/{id:[1-9][0-9]*}', 'updateProfileAction');
$profilesCollection->delete('/{id:[1-9][0-9]*}', 'deleteProfileAction');
$app->mount($profilesCollection);