<?php

namespace App\Models\Mysql;


use App\Lib\Pool\MysqlObject;
use App\Lib\Pool\MysqlPool;
use EasySwoole\EasySwoole\Config as GConfig;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Utility\Schema\Table;

/** @ODM\Document */
class Base extends AbstractModel
{
    /**
     * 表的定义
     * 此处需要返回一个 EasySwoole\ORM\Utility\Schema\Table
     * @return Table
     */
    protected function schemaInfo(): Table
    {
    }
}
