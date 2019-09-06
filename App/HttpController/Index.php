<?php

namespace App\HttpController;

use App\Models\User;
use EasySwoole\Http\AbstractInterface\Controller;
use MongoDB\Client;
use MongoDB\Tests\Model\CollectionInfoTest;

/**
 * Class Index
 * @package App\HttpController
 */
class Index extends Controller
{
    /**
     * 首页方法
     * @author : evalor <master@evalor.cn>
     */
    function index()
    {
        $this->orm();
        $this->response()->withHeader('Content-type', 'text/html;charset=utf-8');
        $this->response()->write('<div style="text-align: center"><a href="https://www.easyswoole.com/Manual/2.x/Cn/_book/Base/http_controller.html">查看手册了解详细使用方法</a></div></br>');
    }

    public function orm()
    {
        $con = new \MongoDB\Driver\Manager("mongodb://localhost:27017");



    }
}
