<?php
namespace App\Models\Mysql;


use App\Models\Mysql\BaseMysqlModel;

/** @ODM\Document */
class Group extends BaseMysqlModel
{
    public $tableName = "groups";

}
