<?php
namespace App\Lib\Pool;

use EasySwoole\Component\Pool\AbstractPool;

class RedisPool extends AbstractPool
{
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        $redis = new RedisObject();
        ///$conf = Config::getInstance()->getConf('REDIS');
        $conf = \Yaconf::get("redis");
        if( $redis->connect($conf['host'],$conf['port'])){
            if(!empty($conf['auth'])){
                $redis->auth($conf['auth']);
            }
            return $redis;
        }else{
            return null;
        }
    }
}