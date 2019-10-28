<?php
namespace App\Models\Mysql;



use App\Models\Mysql\BaseMysqlModel;

/** @ODM\Document */
class Relation extends BaseMysqlModel
{
    public $tableName = "relations";

    public function createSelf($data)
    {

    }

    /**
     * @param $userId
     * @throws \Throwable
     */
    public function userRelation($userId)
    {
        return $this->where(['user_id' => $userId])->field(['friend_id', 'group_id', 'config_id', 'created_at'])->all();
    }

}
