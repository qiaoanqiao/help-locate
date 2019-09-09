<?php
/**
 * Generate a more truly "random" alpha-numeric string.
 *
 * @param  int  $length
 * @return string
 */
function random($length = 16)
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
 * @return \Swoole\Coroutine\Redis
 * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
 * @throws \EasySwoole\Component\Pool\Exception\PoolException
 */
function redisPool()
{
    $redis = \App\Lib\Pool\RedisPool::defer();

    return $redis;
}
//
//if (! function_exists('value')) {
//
//    /**
//     * Gets the value of an environment variable.
//     *
//     * @param string $key
//     * @param mixed $default
//     * @return mixed
//     */
//    function env($key, $default = null)
//    {
//        $value = getenv($key);
//
//        if ($value === false) {
//            return value($default);
//        }
//
//        switch (strtolower($value)) {
//            case 'true':
//            case '(true)':
//                return true;
//            case 'false':
//            case '(false)':
//                return false;
//            case 'empty':
//            case '(empty)':
//                return '';
//            case 'null':
//            case '(null)':
//                return;
//        }
//
//        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
//            return substr($value, 1, -1);
//        }
//
//        return $value;
//    }
//}

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

function isDebug()
{
    return \EasySwoole\EasySwoole\Config::getInstance()->getConf('debug');
}