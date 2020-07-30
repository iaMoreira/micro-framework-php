<?php

use Phroute\Phroute\RouteCollector;


$router = new RouteCollector();
$router->filter('auth', ['App\Middleware\Authenticate', 'handle']);
$router->filter('owner', ['App\Middleware\ResourceOwner', 'handle']);

$router->group(['prefix' => request()->base()], function ($router) {
    $router->post('login',  ['App\Controllers\LoginController', 'login']);
    $router->post('users',  ['App\Controllers\UserController', 'store']);

    $router->group(['before' => 'auth', 'prefix' => 'users'], function ($router) {

        $router->get('drinks/ranking',  ['App\Controllers\DrinkController', 'rankingToday']);
        $router->get('/',  ['App\Controllers\UserController', 'index']);
        $router->get('/{id}',  ['App\Controllers\UserController', 'show']);
        $router->get('/{userId}/drink',  ['App\Controllers\DrinkController', 'index']);

        $router->group(['before' => 'owner'], function ($router) {
            $router->put('/{id}',  ['App\Controllers\UserController', 'update']);
            $router->delete('/{id}',  ['App\Controllers\UserController', 'destroy']);
            $router->post('/{userId}/drink',  ['App\Controllers\DrinkController', 'store']);
        });
    });
});

$resolver = new App\Providers\RouterResolverProvider($container);
$response = (new Phroute\Phroute\Dispatcher($router->getData(), $resolver))->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));