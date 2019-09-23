<?php

namespace App\Lib\Redis;
ini_set('default_socket_timeout', -1);

//use EasySwoole\Core\AbstractInterface\Singleton;
use App\Lib\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Component\Singleton;

class Redis
{

    use Singleton;
    private static $instance;

    /**
     * @var \Redis|RedisPool
     */
    public $redis = null;

    public $round = 0;

    /**
     * Redis constructor.
     * @throws \Throwable
     */
    private function __construct()
    {
        ini_set('default_socket_time', -1);
        $this->initRedis();
    }

    public function close()
    {
        if(empty($this->redis)) {
            if($this->redis instanceof \Redis) {
                $this->redis->close();
            } else {
                PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redis);
            }
            $this->redis = null;
//            unset($this->redis);
        }


    }

    /**
     * @throws \Throwable
     */
    private function initRedis()
    {
        if (empty($this->redis)) {
            try {
                $this->redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
                //如果获取不到进程池的对象
            } catch (\Exception $e) {
                $redisConfig = \EasySwoole\EasySwoole\Config::getInstance()->getConf("REDIS");
                $this->redis = new \Redis();
                $result = $this->redis->connect($redisConfig['host'], $redisConfig['port'], $redisConfig['time_out']);
                if ($result === false) {
                    throw new \Exception("进程池申请异常后 普通redis 链接失败");
                }
            }
        }
        if (empty($this->redis)) {
            $redisConfig = \EasySwoole\EasySwoole\Config::getInstance()->getConf("REDIS");
            $this->redis = new \Redis();
            $result = $this->redis->connect($redisConfig['host'], $redisConfig['port'], $redisConfig['time_out']);
            if ($result === false) {
                throw new \Exception("redis 链接失败");
            }
        }
    }

    /**
     * [get description]
     * @auth   singwa
     * @date   2018-10-07T21:19:29+0800
     * @param  [type]                   $key [description]
     * @return [type]                        [description]
     */
    public function get($key)
    {
        if (empty($key)) {
            return '';
        }

        return $this->getRedis()->get($key);
    }

    /**
     * [set description]
     * @auth  singwa
     * @param [type]  $key   [description]
     * @param [type]  $value [description]
     * @param integer $time [description]
     */
    public function set($key, $value, $time = 0)
    {
        if (empty($key)) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (!$time) {
            return $this->getRedis()->set($key, $value);
        }
        return $this->getRedis()->setex($key, $time, $value);
    }

    public function lPop($key)
    {
        if (empty($key)) {
            return '';
        }

        return $this->getRedis()->lPop($key);
    }

    /**
     * [rPush description]
     * @auth   singwa
     * @date   2018-10-13T23:45:42+0800
     * @param  [type]                   $key   [description]
     * @param  [type]                   $value [description]
     * @return [type]                          [description]
     */
    public function rPush($key, $value)
    {
        if (empty($key)) {
            return '';
        }

        return $this->getRedis()->rPush($key, $value);
    }

    public function zincrby($key, $number, $member)
    {
        if (empty($key) || empty($member)) {
            return false;
        }

        return $this->getRedis()->zincrby($key, $number, $member);
    }



    /**
     * 当类中不存在该方法时候，直接调用call 实现调用底层redis相关的方法
     * @auth   singwa
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($name, $arguments)
    {
        return $this->getRedis()->$name(...$arguments);
    }

    /**
     * @return RedisPool|\Redis
     * @throws \Throwable
     */
    public function getRedis()
    {
        if(empty($this->redis)) {
            $this->initRedis();
        }
        return $this->redis;
    }


}