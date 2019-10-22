<?php
namespace App\Lib\Pool;

use EasySwoole\Pool\ObjectInterface;
use Swoole\Coroutine\Redis;

class RedisObject extends \EasySwoole\Redis\Redis implements ObjectInterface
{
    function gc()
    {
        // TODO: Implement gc() method.
    }

    function objectRestore()
    {
        // TODO: Implement objectRestore() method.
    }

    function beforeUse(): bool
    {
        // TODO: Implement beforeUse() method.
        return true;
    }
}
