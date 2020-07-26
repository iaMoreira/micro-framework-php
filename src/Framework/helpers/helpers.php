<?php

use \Framework\View;
use \Framework\Router;
use \Framework\Request;
use Framework\Response;
use Framework\Flash;
use Framework\Validate;

/**
 * ==============================================================================================================
 *
 * Helpers: funções auxiliares para construção de aplicativos
 *
 * ----------------------------------------------------
 *
 * @author Alexandre Bezerra Barbosa <alxbbarbosa@yahoo.com.br>
 * @copyright (c) 2018, Alexandre Bezerra Barbosa
 * @version 1.00
 * ==============================================================================================================
 */
function view($view_file, $data = [])
{
    // $view = new View();

    return $view->render($view_file, $data);
}

function request($key = null)
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

function session()
{
    // return new Flash;
}

function validate()
{
    // return new Validate;
}

function old($field)
{
    return session()->getOld($field);
}
