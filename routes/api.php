<?php

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

$router = \App\HttpController\Router::$routeObj;

/** @var FastRoute\RouteCollector $router */
$router->get('/user', '/UserAuth/index');
$router->get('/', function (Request $request, Response $response) {
    $response->write('this router index');
});