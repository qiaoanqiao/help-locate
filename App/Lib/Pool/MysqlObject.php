<?php
namespace App\Lib\Pool;
use EasySwoole\ORM\DbManager;
use EasySwoole\Pool\ObjectInterface;

class MysqlObject implements ObjectInterface
{
    function gc()
    {
        DbManager::getInstance();
        // 重置为初始状态
        $this->resetDbStatus();
        // 关闭数据库连接
        $this->getMysqlClient()->close();
    }

    function objectRestore()
    {
        // 重置为初始状态
        $this->resetDbStatus();
    }

    /**
     * 每个链接使用之前 都会调用此方法 请返回 true / false
     * 返回false时PoolManager会回收该链接 并重新进入获取链接流程
     * @return bool 返回 true 表示该链接可用 false 表示该链接已不可用 需要回收
     */
    function beforeUse(): bool
    {
        // 此处可以进行链接是否断线的判断 使用不同的数据库操作类时可以根据自己情况修改
        return $this->getMysqlClient()->connected;
    }
}
