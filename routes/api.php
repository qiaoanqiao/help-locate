<?php

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

/** @var FastRoute\RouteCollector $router */
$router->get('/user', '/UserAuth/index');
$router->get('/', '/Index/index');
$router->get('/validate/graphical-verification', '/Validation/getGraphicsBase64');
$router->post('/validate/send-sms-code', '/Validation/sendSmsCode');
$router->post('/user/register', '/UserAuth/register');
$router->post('/user/login', '/UserAuth/login');