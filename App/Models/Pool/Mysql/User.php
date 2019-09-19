<?php
namespace App\Models\Pool\Mysql;


use App\Models\Pool\Mysql\Base;
use EasySwoole\Mysqli\Exceptions\ConnectFail;
use EasySwoole\Mysqli\Exceptions\PrepareQueryFail;

/** @ODM\Document */
class User extends Base
{
    public $tableName = "user";

    public function mobileCreateUser($mobile, $password)
    {
        $userInserData = [
            'name' => $this->defaultName(),
            'note_name' => $this->defaultNoteName(),
            'mobile' => $mobile,
            'email' => '',
            'wx_id' => '',
            'password' => $password,
            'is_vip' => $this->defaultIsVip(),
        ];

        $insert = $this->insert($userInserData);
        if($insert > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function defaultName()
    {
        $count = ($this->count('id') ?: 0) + 1;
        try {
            return '默认昵称_' . $count;
        } catch (\Throwable $e) {
            return 1;
        }
    }

    public function defaultNoteName()
    {
        return '';
    }

    public function defaultIsVip()
    {
        return 0;
    }

    /**
     * @param $mobile
     * @return bool
     * @throws ConnectFail
     * @throws PrepareQueryFail
     * @throws \EasySwoole\Mysqli\Exceptions\Option
     * @throws \Throwable
     */
    public function hasWhetherUserOnly($mobile)
    {
        return $this->db->where('mobile', $mobile)->has($this->tableName);
    }

    public function __call($name, $arguments)
    {
        return $this->db->$name(...$arguments);
    }
}