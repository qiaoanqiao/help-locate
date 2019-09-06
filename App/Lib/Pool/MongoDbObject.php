<?php
namespace App\Lib\Pool;

use EasySwoole\Component\Pool\PoolObjectInterface;
use MongoDB\Client;
use MongoDB\Collection;
use Swoole\Coroutine\Redis;

class MongoDbObject extends Client implements PoolObjectInterface
{
    function gc()
    {
        // TODO: Implement gc() method.
        $this->close();
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