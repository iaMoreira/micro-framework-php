<?php

use Framework\Request;
use Framework\Router;

// include __DIR__ . '/vendor/autoload.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use App\Controllers\UserController;

$collector = new RouteCollector();
$userController = new UserController();

$collector->get('api/public/users',  [$userController, 'index']);
$collector->post('api/public/users',  [$userController, 'store']);
$collector->get('api/public/users/{id}',  [$userController, 'show']);
$collector->put('api/public/users/{id}',  [$userController, 'update']);
$collector->delete('api/public/users/{id}',  [$userController, 'destroy']);

$dispatcher = new Dispatcher($collector->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));