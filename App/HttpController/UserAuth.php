<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Models\Pool\Mysql\User;
use App\RequestValidate\UserLoginRequest;
use EasySwoole\Validate\Validate;

/**
 * Class Index
 * @package App\HttpController
 */
class UserAuth extends BaseController
{
    /**
     * 首页方法
     * @author : evalor <master@evalor.cn>
     */
    function index()
    {
        var_dump(1);
    }

    public function orm()
    {

        $model = new User();
        $data = $model->find(1);
        var_dump($data);
    }

    protected function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action) {
            case 'ts':
                {
                    $validate = (new UserLoginRequest($v))->getValObj();
                    break;
                }
            default:
                {
                    $validate = null;
                }
        }

        return $validate;
    }

}
