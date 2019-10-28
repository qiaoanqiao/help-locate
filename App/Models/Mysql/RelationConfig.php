<?php
namespace App\Models\Mysql;

use App\Models\Mysql\BaseMysqlModel;

/** @ODM\Document */
class RelationConfig extends BaseMysqlModel
{
    public $tableName = "relation_configs";

    /**
     * @param $data
     * @return bool|int
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function createSelf($data)
    {
    }
}
