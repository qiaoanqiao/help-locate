<?php

namespace App\Models\Pool\Mysql;


use App\Lib\Pool\MysqlObject;
use App\Lib\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config as GConfig;
use EasySwoole\Mysqli\Exceptions\ConnectFail;
use EasySwoole\Mysqli\Exceptions\PrepareQueryFail;

/** @ODM\Document */
class Base
{
    public $db;
    public $query;
    public $tableName;
    protected $config;

    /**
     * Base constructor.
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->config = GConfig::getInstance()->getConf('MYSQL');
        $mysqlObject = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj($this->config['POOL_TIME_OUT']);
        // 类型的判定
        if ($mysqlObject instanceof MysqlObject) {
            $this->db = $mysqlObject;
        } else {
            throw new \Exception('Mysql Pool is error');
        }
    }

    public function __destruct()
    {
        if ($this->db instanceof MysqlObject) {
            PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($this->db);
            // 请注意 此处db是该链接对象的引用 即使操作了回收 仍然能访问
            // 安全起见 请一定记得设置为null 避免再次使用导致不可预知的问题
            $this->db = null;
        }
    }

    /**
     * 通过ID 获取 基本信息
     *
     * @param [type] $id
     * @return array
     */
    public function find($id) : array
    {
        $id = intval($id);
        if (empty($id)) {
            return [];
        }

        $this->db->where("id", $id);
        $result = $this->db->getOne($this->tableName);
        return $result ?? [];
    }

    /**
     * @param array $column
     * @return array
     * @throws ConnectFail
     * @throws PrepareQueryFail
     * @throws \Throwable
     */
    public function all(array $column = []) : array
    {
        $data = $this->db->get($this->tableName, null, '*');
        return $data;
    }

}