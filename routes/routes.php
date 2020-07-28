<?php

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

$router = new RouteCollector();

$router->post('api/public/login',  ['App\Controllers\LoginController', 'login']);
$router->post('api/public/users',  ['App\Controllers\UserController', 'store']);

$router->filter('auth', ['App\Middleware\Authenticate', 'handle']);
$router->filter('owner', ['App\Middleware\ResourceOwner', 'handle']);

$router->group(['before' => 'auth'], function ($router) {

    $router->get('api/public/users',  ['App\Controllers\UserController', 'index']);
    $router->get('api/public/users/{id}',  ['App\Controllers\UserController', 'show']);
    
    $router->group(['before' => 'owner'], function ($router) {
        $router->put('api/public/users/{id}',  ['App\Controllers\UserController', 'update']);
        $router->delete('api/public/users/{id}',  ['App\Controllers\UserController', 'destroy']);
        $router->post('api/public/users/{userId}/drink',  ['App\Controllers\DrinkController', 'customStore']);    
    });
});


$dispatcher = new Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
