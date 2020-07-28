<?php

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

$collector = new RouteCollector();

$collector->get('api/public/users',  ['App\Controllers\UserController', 'index']);
$collector->post('api/public/users',  ['App\Controllers\UserController', 'store']);
$collector->get('api/public/users/{id}',  ['App\Controllers\UserController', 'show']);
$collector->put('api/public/users/{id}',  ['App\Controllers\UserController', 'update']);
$collector->delete('api/public/users/{id}',  ['App\Controllers\UserController', 'destroy']);
$collector->post('api/public/users/{userId}/drink',  ['App\Controllers\DrinkController', 'customStore']);

$collector->post('api/public/login',  ['App\Controllers\LoginController', 'login']);

$dispatcher = new Dispatcher($collector->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
