<?php
/**
 * Generate a more truly "random" alpha-numeric string.
 *
 * @param int $length
 * @return string
 * @throws Exception
 */
function str_random($length = 16)
{
    $string = '';

    while (($len = strlen($string)) < $length) {
        $size = $length - $len;

        $bytes = random_bytes($size);

        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }

    return $string;
}

/**
 * 获取 redis 进程池
 * @return \Swoole\Coroutine\Redis
 */
function redis_pool()
{
    try {
        $redis = \App\Lib\Pool\RedisPool::defer();
    } catch (\Exception $e) {
        $redis = \App\Lib\Redis\Redis::getInstance();
    }

    return $redis;
}

/**
 * 字符串精准要求高的验证场景的对比
 *
 * @param $string
 * @param $lastString
 * @return bool
 */
function string_coomp($string, $lastString) : bool
{
    return hash_equals($string, $lastString);
}

/**
 * Return the default value of the given value.
 *
 * @param  mixed  $value
 * @return mixed
 */
function value($value)
{
    return $value instanceof Closure ? $value() : $value;
}

function isDebug() :bool
{
    return \EasySwoole\EasySwoole\Config::getInstance()->getConf('debug') ? true : false;
}


/**
 * 随机生成不同验证类型和场景的缓存 Key
 *
 * @param string $scenario
 * @param string $autype
 * @return string
 */
function randomScenariosKey($length = 12)
{
    return \EasySwoole\Utility\Random::character($length);
}

function config($key)
{
    return \EasySwoole\EasySwoole\Config::getInstance()->getConf($key);
}

function ec($data)
{
    var_dump($data);
}


