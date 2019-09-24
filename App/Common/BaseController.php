<?php


namespace App\Common;

use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Response;
use EasySwoole\Validate\Validate;

class BaseController extends Controller
{
    use JsonResponseTrait;

    public $requestParams = null;

    private $validationKeyConf = [];

    public function __construct()
    {
        parent::__construct();

        $this->validationKeyConf = Config::getInstance()->getConf('validation_scenarios');
    }


    public function index()
    {
        // TODO: Implement index() method.
    }

    /**
     * @param string|null $action
     * @param Response $response
     * @return bool|null
     */
    protected function onRequest(?string $action): ?bool
    {
        $response = $this->response();
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $request = $this->request();
        if ($request->getMethod() === 'OPTIONS') {
            $response->withStatus(Status::CODE_OK);
            return false;
        }
        $ret =  parent::onRequest($action);

        //中间件方法 todo 路由中间件
        if(method_exists($this, 'leadMiddleware')) {
            if(($middleWare = $this->leadMiddleware()) !== true) {

                return false;
            }
        }
        if($ret === false){
            return false;
        }
        $v = $this->validateRule($action);
        if($v){
            $ret = $this->validate($v);

            if($ret == false){
                $this->error522("{$v->getError()->getField()}@{$v->getError()->getFieldAlias()}:{$v->getError()->getErrorRuleMsg()}",[$v->getError()->getField() => "{$v->getError()->getFieldAlias()}:{$v->getError()->getErrorRuleMsg()}"]);
                return false;
            }
        }
        return true;
    }

    protected function validateRule(?string $action): ?Validate
    {

        return null;
    }

    protected function allRequestParams()
    {
        if($this->requestParams === null) {
            $this->requestParams = $this->request()->getRequestParam();
        }

        return $this->requestParams;
    }

    /**
     * 获取请求参数
     *
     * @param $key
     * @return mixed|null
     */
    public function requestParam($key = null)
    {
        if($this->requestParams === null) {
            $this->requestParams = $this->request()->getRequestParam();
        }
        if($key === null) {
            return $this->allRequestParams();
        }

        return $this->requestParams[$key] ?? null;
    }

    public function getCachePool()
    {
        return \App\Lib\Redis\Redis::getInstance();
    }

    protected function afterAction(?string $actionName): void
    {

    }


}