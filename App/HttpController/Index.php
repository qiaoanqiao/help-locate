<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Models\Mysql\User;
use EasySwoole\ORM\DbManager;

/**
 * Class Index
 * @package App\HttpController
 */
class Index extends BaseController
{
    /**
     * 首页方法
     * @author : evalor <master@evalor.cn>
     */
    public function index()
    {
        DbManager::getInstance()->onQuery(function (\EasySwoole\ORM\Db\Result $onQuery,\EasySwoole\Mysqli\QueryBuilder $count = null,$temp = '',$start =''){
            var_dump($count);
        });
        $data = User::create()->get()->toArray();
        var_dump($data);
        return $this->success200();
    }

}
