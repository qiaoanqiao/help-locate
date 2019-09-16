<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/15
 * Time: 上午10:39
 */

namespace App\HttpController;

use EasySwoole\Component\Singleton;
use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter
{
//    public static $routeObj;

    function initialize(RouteCollector $routeCollector)
    {
        $router = $routeCollector;
        $this->setGlobalMode(true);
        $this->map($routeCollector);
    }

    public function map($router)
    {
        $this->mapApiRoutes($router);
        $this->mapSpecialRoutes($router);
    }

    public function mapSpecialRoutes($router)
    {
        $this->setMethodNotAllowCallBack(function (Request $request,Response $response){
            $this->writeJson(500, [], '不存在的业务地址!', $response);
            return false;//结束此次响应
        });
        $this->setRouterNotFoundCallBack(function (Request $request,Response $response){
            $this->writeJson(500, [], '不存在的接口地址!', $response);
            return false;
        });
    }

    public function mapApiRoutes($router)
    {
        return include_once(RUNNING_ROOT . '/routes/api.php');
    }

    protected function writeJson($statusCode = 200, $result = null, $msg = null, Response $response = null)
    {

        if (!$response->isEndResponse()) {
            $data = Array(
                "code" => $statusCode,
                "result" => $result,
                "msg" => $msg
            );
            $response->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            $response->withStatus($statusCode);
            return true;
        } else {
            return false;
        }
    }
}