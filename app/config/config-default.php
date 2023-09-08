<?php

return new  Phalcon\Config\Config(
    [
        'database' => [
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'g25s10m99',
            'dbname' => 'generic-backend',
        ],

        'application' => [
	        'controllersDir' => "app/controllers/",
	        'modelsDir'      => "app/models/",
	        'baseUri'        => "/",
        ],
    ]
);
