<?php
namespace App\Lib\Redis;
ini_set('default_socket_timeout', -1);
//use EasySwoole\Core\AbstractInterface\Singleton;
use EasySwoole\Component\Singleton;
use EasySwoole\Config;
class Redis {
	
	use Singleton;

	public $redis = "";

	private function __construct() {
		ini_set('default_socket_time', -1);
		if(!extension_loaded('redis')) {
			throw new \Exception("redis.so文件不存在");
		}
		try {
			//$redisConfig = Config::getInstance()->getConf("redis");
			
			$redisConfig = \Yaconf::get('redis');
			$this->redis = new \Redis();
			$result = $this->redis->connect($redisConfig['host'], $redisConfig['port'], $redisConfig['time_out']);
		} catch(\Exception $e) {
			throw new \Exception("redis服务异常");
		}

		if($result === false) {
			throw new \Exception("redis 链接失败");
		} 
	}

	/**
	 * [get description]
	 * @auth   singwa
	 * @date   2018-10-07T21:19:29+0800
	 * @param  [type]                   $key [description]
	 * @return [type]                        [description]
	 */
	public function get($key) {
		if(empty($key)) {
			return '';
		}

		return $this->redis->get($key);
	}

	/**
	 * [set description]
	 * @auth  singwa
	 * @param [type]  $key   [description]
	 * @param [type]  $value [description]
	 * @param integer $time  [description]
	 */
	public function set($key, $value, $time = 0) {
		if(empty($key)) {
			return '';
		}
		if(is_array($value)) {
			$value = json_encode($value);
		}
		if(!$time) {
			return $this->redis->set($key, $value);
		}
		return $this->redis->setex($key, $time, $value);
	}

	public function lPop($key) {
		if(empty($key)) {
			return '';
		}

		return $this->redis->lPop($key);
	}

	/**
	 * [rPush description]
	 * @auth   singwa
	 * @date   2018-10-13T23:45:42+0800
	 * @param  [type]                   $key   [description]
	 * @param  [type]                   $value [description]
	 * @return [type]                          [description]
	 */
	public function rPush($key, $value) {
		if(empty($key)) {
			return '';
		}

		return $this->redis->rPush($key, $value);
	}

	public function zincrby($key, $number, $member) {
		if(empty($key) || empty($member)) {
			return false;
		}

		return $this->redis->zincrby($key, $number, $member);
	}

	/**
	 * 当类中不存在该方法时候，直接调用call 实现调用底层redis相关的方法
	 * @auth   singwa
	 * @param  [type] $name      [description]
	 * @param  [type] $arguments [description]
	 * @return [type]            [description]
	 */
	public function __call($name, $arguments) {
		
		///var_dump(...$arguments);
		return $this->redis->$name(...$arguments);
	}


}