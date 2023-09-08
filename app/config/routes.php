<?php

// Modules Routes
require __DIR__ . '/modules/users.php';
require __DIR__ . '/modules/logs.php';
require __DIR__ . '/modules/modules.php';
require __DIR__ . '/modules/permissions.php';
require __DIR__ . '/modules/profiles.php';
require __DIR__ . '/modules/rules.php';

// Not Found
$app->notFound(
  function () use ($app) {
      $exception =
        new \App\Controllers\HttpExceptions\Http404Exception(
          _('URI not found or error in request.'),
          \App\Controllers\AbstractController::ERROR_NOT_FOUND,
          new \Exception('URI not found: ' . $app->request->getMethod() . ' ' . $app->request->getURI())
        );
      throw $exception;
  }
);
