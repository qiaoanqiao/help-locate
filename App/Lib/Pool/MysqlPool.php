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
        $config->setDatabase('easyswoole_orm');
        $config->setUser('root');
        $config->setPassword('');
        $config->setHost('127.0.0.1');
        $conf = Config::getInstance()->getConf("MYSQL");
        $dbConf = new \EasySwoole\Mysqli\Config($conf);
        return new MysqlObject($dbConf);
    }
}
