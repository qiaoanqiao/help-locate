<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Models\Pool\Mysql\User;

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
        return $this->success200();
    }

}
