<?php
namespace App\Lib\Pool;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Log\Logger;
use MongoDB\Client;
use MongoDB\Driver\Exception\InvalidArgumentException as DriverInvalidArgumentException;
use MongoDB\Driver\Exception\RuntimeException as DriverRuntimeException;
use MongoDB\Exception\InvalidArgumentException;

class MongoDbPool extends AbstractPool
{
    protected function createObject()
    {
        $config = \EasySwoole\EasySwoole\Config::getInstance()->getConf("MONGODB");

        try {
            $db = $config['database'];
            $databasesObj = (new Client($config['dsn']))->$db;
        } catch (\Exception $e) {
            Logger::getInstance()->error('Mongodb 连接池有一个初始化失败 异常信息:' . $e->getTraceAsString());

            return null;
        }


        $insertOneResult = $databasesObj->users->insertOne([
            '_id' => rand(0, 100),
            'username' => 'pool',
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);

        return $databasesObj;
    }
}