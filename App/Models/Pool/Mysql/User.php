<?php
namespace App\Models\Pool\Mysql;


use App\Models\Pool\Mysql\Base;

/** @ODM\Document */
class User extends Base
{
    public $tableName = "user";

    public function mobileCreateUser($mobile)
    {
        $userInserData = [
            'name' => '',
            'note_name' => '',
            'mobiel' => $mobile,
            'email' => '',
            'wx_id' => '',
            'is_vip' => '',
        ];
    }
}