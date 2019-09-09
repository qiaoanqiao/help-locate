<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Models\Pool\Mysql\User;
use App\RequestValidate\UserLoginRequest;
use App\RequestValidate\UserRegisterRequest;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Validate\Validate;

/**
 * Class Index
 * @package App\HttpController
 */
class UserAuth extends BaseController
{
    public function index()
    {
        var_dump(isDebug());
    }

    public function isDebug()
    {
        return 1;
    }

    public function register()
    {

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
            case 'login':
                {
                    $validate = (new UserLoginRequest($v))->getValObj();
                    break;
                }
            case 'register':
                {
                    $validate = (new UserRegisterRequest($v))->getValObj();
                }
            default:
                {
                    $validate = null;
                }
        }

        return $validate;
    }

}
