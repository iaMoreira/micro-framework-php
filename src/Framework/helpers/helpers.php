<?php

use \Framework\Router;
use \Framework\Request;
use Framework\Response;
use Framework\Validate;


function request($key = null): Request
{
    $request = new Request();

    return !is_null($key) ? $request->get($key) : $request;
}

function dd($data)
{
    var_dump($data);
    die();
}

function route($name)
{
    if (func_num_args() > 1) {
        $args   = func_get_args();
        $name   = array_shift($args);
        $params = $args;
    } else {
        $params = [];
    }
    return router()->name($name, $params);
}

function router($uri = null, $method = 'get')
{
    $router = new Router();
    if ($uri) {
        return $router->find($method, $uri);
    }
    return $router;
}

function response($content = '', $status = 200, $headers = [], $charset = 'UTF-8')
{
    return new Response($content, $status, $headers, $charset);
}

function validate()
{
    return new Validate;
}

function bcrypt(string $value): string
{
    return password_hash($value, PASSWORD_BCRYPT);
}
