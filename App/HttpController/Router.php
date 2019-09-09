<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/15
 * Time: 上午10:39
 */

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter
{
    public static $routeObj;

    function initialize(RouteCollector $routeCollector)
    {
        self::$routeObj = $routeCollector;
        $this->setGlobalMode(true);
        $this->map();
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapSpecialRoutes();

    }

    public function mapSpecialRoutes()
    {
        $this->setMethodNotAllowCallBack(function (Request $request,Response $response){
            $response->write('未找到处理方法');
            return false;//结束此次响应
        });
        $this->setRouterNotFoundCallBack(function (Request $request,Response $response){
            $response->write('未找到路由匹配');
            return 'index';//重定向到index路由
        });
    }

    public function mapApiRoutes()
    {
        include_once(RUNNING_ROOT . '/routes/api.php');
    }
}