<?php
namespace App\Models\Mysql;


use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Utility\Schema\Table;

/** @ODM\Document */
class User extends AbstractModel
{
    public $tableName = "users";

    /**
     * 表的定义
     * 此处需要返回一个 EasySwoole\ORM\Utility\Schema\Table
     * @return Table
     */
    protected function schemaInfo(): Table
    {
        $table = new Table('users');
        $table->colInt('id')->setIsPrimaryKey(true);
        $table->colVarChar('name', 30);
        $table->colVarChar('note_name', 30);
        $table->colVarChar('mobile', 30);
        $table->colVarChar('password', 125);
        $table->colVarChar('email', 40);
        $table->colVarChar('avatar', 125);
        $table->colVarChar('wx_id', 100);
        $table->colTinyInt('is_vip', 1);
        $table->colTimestamp('created')->setDefaultValue('CURRENT_TIMESTAMP');
        return $table;
    }

    public function mobileCreateUser($mobile, $password)
    {
        /** @var AbstractModel $model */
        $model = new $this([
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
            $model = User::create()->field('id')->withTotalCount();
            $model->all();
            $count = $model->lastQueryResult()->getTotalCount();
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
