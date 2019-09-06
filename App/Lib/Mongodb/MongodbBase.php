<?php
namespace App\Lib\Mongodb;

use EasySwoole\Log\Logger;
use MongoDB\Client;
use MongoDB\Database;

class MongodbBase {
    protected $connection;

    public function __construct()
    {
        $config = \EasySwoole\EasySwoole\Config::getInstance()->getConf("MONGODB");
        $connection = new \MongoDB\Client($config['dsn']);
        $this->connection = $connection;
        $this->selectDatabase($config['database']);

        try {
            $db = $config['database'];
            $databasesObj = (new Client($config['dsn']))->$db;
        } catch (\Exception $e) {
            Logger::getInstance()->error('Mongodb 连接池有一个初始化失败 异常信息:' . $e->getTraceAsString());

            return null;
        }
    }

    function selectCollection($databaseName) {

    }

    protected function selectDatabase ( $databaseName) {
        $testDb = $this->connection->selectDatabase($databaseName);
        $testDb->selectCollection('sleepcol')->updateOne(['_id' => 1], [
            '$set' => [
                '_id' => 1,
                'desc' => 'Single doc in collection for consistent sleep behaviour'
            ]
        ], [
            'upsert' => true
        ]);
    }
}