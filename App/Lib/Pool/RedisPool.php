<?php
namespace App\Lib\Pool;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Component\Pool\PoolObjectInterface;
use EasySwoole\EasySwoole\Config;

class RedisPool extends AbstractPool
{

    protected function createObject()
    {
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        $conf = Config::getInstance()->getConf('REDIS');
        $redis = new RedisObject();
        $connected = $redis->connect($conf['host'], $conf['port']);
        if($connected){
            if(!empty($conf['auth'])){
                $redis->auth($conf['auth']);
            }
            //选择数据库,默认为0
            if(!empty($conf['db'])){
                $redis->select($conf['db']);
            }
            return $redis;
        }else{
            return null;
        }
    }
}