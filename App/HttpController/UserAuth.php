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
        return $this->success200();
    }

    public function register(User $user)
    {
        $validation_scenarios = $this->requestParam('validation_scenarios');
        $captcha_image_key = $this->requestParam('captcha_sms_key');
        $captcha_code = $this->requestParam('captcha_sms_code');
        $mobile = $this->requestParam('mobile');

        $validation = new Validation();
        if($valMessage = $validation->verifyMobileVerificationCode($validation_scenarios, $captcha_image_key, $captcha_code, $mobile) !== true) {
            return $this->error522($valMessage);
        }


        return $this->success200();
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
