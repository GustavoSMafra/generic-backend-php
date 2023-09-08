<?php

$loader = new \Phalcon\Autoload\Loader();
$loader->setNamespaces(
  [
    'App\Services'    => realpath(__DIR__ . '/../services/'),
    'App\Controllers' => realpath(__DIR__ . '/../controllers/'),
    'App\Models'      => realpath(__DIR__ . '/../models/'),
  ]
);

$loader->register();
