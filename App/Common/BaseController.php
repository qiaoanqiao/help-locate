<?php


namespace App\Common;

use App\Lib\IpList;
use App\Lib\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\Validate\Validate;

class BaseController extends Controller
{
    use JsonResponseTrait;

    public $requestParams = null;

    public $redisPool = null;

    public $validationKeyConf = [];

    public function __construct()
    {
        parent::__construct();

        $this->validationKeyConf = Config::getInstance()->getConf('validation_scenarios');
        if($this->redisPool === null) {
            try {
                $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
            } catch (\Exception $e) {
                $redis = \App\Lib\Redis\Redis::getInstance();
            }
            $this->redisPool = $redis;
        }
    }

    public function __destruct()
    {
        if($this->redisPool !== null) {
            //关闭 redis 连接
            if($this->redisPool instanceof \App\Lib\Redis\Redis) {
                $this->redisPool->close;
            } else {
                PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redisPool);
            }
        }
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    protected function onRequest(?string $action): ?bool
    {
        $ret =  parent::onRequest($action);
        if($ret === false){
            return false;
        }
        $v = $this->validateRule($action);
        if($v){
            $ret = $this->validate($v);

            if($ret == false){
                $this->writeJson(Status::CODE_BAD_REQUEST,null,"{$v->getError()->getField()}@{$v->getError()->getFieldAlias()}:{$v->getError()->getErrorRuleMsg()}");
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
        if($this->redisPool === null) {
            try {
                $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
            } catch (\Exception $e) {
                $redis = \App\Lib\Redis\Redis::getInstance();
            }
            $this->redisPool = $redis;
        }
        return $this->redisPool;
    }



}