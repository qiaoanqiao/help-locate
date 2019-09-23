<?php


namespace App\Lib;


use App\Lib\Redis\Redis;
use EasySwoole\EasySwoole\Config;

class AuthToken
{
    private $prefix = 'token_client_';

    private $clientConf = [
        'default' => [
            'ttl' =>  86400,
            'length' => 20,
        ]
    ];

    private $ttlPrefix = 'token_ttl';

    public function __construct()
    {
        $this->initClitnConf();
    }

    /**
     * 初始化客户端配置
     */
    private function initClitnConf()
    {
        $config = Config::getInstance()->getConf('token_client');
        if($config !== null) {
            $this->clientConf = $config;
        }
    }

    /**
     * 生成 token
     * todo 单客户端token唯一处理
     * @param string $client
     * @param array $data
     * @return string
     */
    public function generateToken(string $client, array $data = [])
    {
        $conf = $this->getClientConf($client);

        /** @var \Redis $redis */
        $redis = Redis::getInstance();
        //token 已存在处理
        while (true) {
            $token = $this->randomScenariosKey($conf['length']);
            $cacheKey = $this->assembleTokenCacheKey($client, $token);
            if(!$redis->hExists($cacheKey, $token)) {
                if($redis->hMSet($cacheKey, $data)) {
                    $redis->expire($cacheKey, $conf['ttl']);
                    break;
                }
            }
        }

        return $token;
    }

    /**
     * 获取 token 赋予的 data
     * @param $client
     * @param $token
     * @return array|string
     */
    public function getTokenAsData($client, $token)
    {
        /** @var \Redis $redis */
        $redis = Redis::getInstance();
        $cacheKey = $this->assembleTokenCacheKey($client, $token);
        //token 已存在处理
        if(!$redis->hExists($cacheKey, $token)) {
            return '当前登录无记录或已过期!';
        }

        if(empty($data = $redis->hGetAll($cacheKey))) {
            return '当前登录信息记录异常!';
        }
        //更新调用时间
        $conf = $this->getClientConf($client);
        $redis->expire($cacheKey, $conf['ttl']);

        return $data;
    }

    public function assembleTokenCacheKey($client, $token)
    {
        return ($this->prefix . $client . '_' . $token);
    }
    /**
     * todo token 数据持久化存储
     * @param $token
     */
    public function setStorage()
    {

    }



    public function assembleClientCacheKey(string $client)
    {
        return ($this->prefix . $client);
    }

    /**
     * @return array
     */
    public function getClientConf($key): array
    {
        return $this->clientConf[$key] ?? $this->clientConf['default'];
    }

    /**
     * @param array $clientConf
     */
    public function setClientConf(array $clientConf): void
    {
        $this->clientConf = $clientConf;
    }

    /**
     * 设置 token 缓存
     * @param $token
     * @param $client
     */
    private function setClientTokenCache($client, $data, $token)
    {

    }

    /**
     * 续期
     * @param $token
     */
    public function renewal($token)
    {

    }

    /**
     * 删除 token
     *
     * @param $client
     * @param $token
     */
    public function deleteToken($client, $token)
    {

    }


    /**
     * 随机生成不同验证类型和场景的缓存 Key
     *
     * @param string $scenario
     * @param string $autype
     * @return string
     */
    private function randomScenariosKey($length = 20)
    {
        return \EasySwoole\Utility\Random::character($length);
    }

}