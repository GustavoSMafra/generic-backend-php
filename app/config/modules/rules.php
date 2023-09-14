<?php

$rulesCollection = new \Phalcon\Mvc\Micro\Collection();
$rulesCollection->setHandler('\App\Controllers\RulesController', true);
$rulesCollection->setPrefix('/rules');
$rulesCollection->post('/add', 'addRuleAction');
$rulesCollection->get('/list', 'getRuleListAction');
$rulesCollection->put('/{id:[1-9][0-9]*}', 'updateRuleAction');
$rulesCollection->delete('/{id:[1-9][0-9]*}', 'deleteRuleAction');
$app->mount($rulesCollection);
