<?php
namespace App\Lib\Pool;

use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\Db\Config;
use EasySwoole\Pool\AbstractPool;


class MysqlPool extends AbstractPool
{
    /**
     * 请在此处返回一个数据库链接实例
     * @return MysqlObject
     */
    protected function createObject()
    {
        $config = new Config();
        $conf = config("MYSQL");
        $config->setDatabase($conf['database']);
        $config->setUser($conf['user']);
        $config->setPassword($conf['password']);
        $config->setHost($conf['host']);
        DbManager::getInstance()->addConnection(new Connection($config));

        $dbConf = new \EasySwoole\Mysqli\Config($conf);
        return new MysqlObject($dbConf);
    }
}
