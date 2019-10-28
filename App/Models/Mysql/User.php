<?php
namespace App\Models\Mysql;


use EasySwoole\ORM\AbstractModel;

/** @ODM\Document */
class User extends BaseMysqlModel
{
    public $tableName = "users";

    /**
     * 使用手机号和密码创建用户
     * @param $mobile
     * @param $password
     * @return bool
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function mobileCreateSelf($mobile, $password)
    {
        $model = new self([
            [
                'name' => $this->defaultName(),
                'note_name' => $this->defaultNoteName(),
                'mobile' => $mobile,
                'email' => '',
                'wx_id' => '',
                'password' => $password,
                'is_vip' => $this->defaultIsVip(),
            ]
        ]);
        $res = $model->save();
        if($res) {
            return true;
        } else {
            return false;
        }
    }

    public function defaultName()
    {
        try {
            $count = $model = User::create()->count('id');
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
     * @throws \Throwable
     */
    public function hasWhetherUserOnly($mobile)
    {
        return  (bool)self::create()->withTotalCount()->field('id')->get(['mobile'=> $mobile])->lastQueryResult()->getTotalCount();
    }

    /**
     * @param $mobile
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function login($mobile)
    {
        return self::create()->get(['mobile'=> $mobile])->toArray();
    }
}
