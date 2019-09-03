<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;


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
        echo '<pre>';
        $arr = get_defined_constants(true);
// 打印 $b
        var_dump(\Yaconf::get('mysql.host'));
//        print_r($arr);
        $this->response()->withHeader('Content-type', 'text/html;charset=utf-8');
        $this->response()->write('<div style="text-align: center"><a href="https://www.easyswoole.com/Manual/2.x/Cn/_book/Base/http_controller.html">查看手册了解详细使用方法</a></div></br>');
    }
}
