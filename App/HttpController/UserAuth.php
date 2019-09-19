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

    public function register()
    {
        $validation_scenarios = $this->requestParam('validation_scenarios');
        $captcha_image_key = $this->requestParam('captcha_sms_key');
        $captcha_code = $this->requestParam('captcha_sms_code');
        $mobile = $this->requestParam('mobile');
        $password = $this->requestParam('password');

        $validation = new Validation();
        if($valMessage = $validation->verifyMobileVerificationCode($validation_scenarios, $captcha_image_key, $captcha_code, $mobile) !== true) {
            return $this->error522($valMessage);
        }

        $user = new User();
        if($user->hasWhetherUserOnly($mobile)) {
            return $this->error522('您的手机号已被注册!', ['mobile' => '您的手机号已被注册!']);
        }

        $insertStatus = $user->mobileCreateUser($mobile, $this->encryption($password));
        if($insertStatus) {
            return $this->success200('注册成功!');
        } else {
            return $this->error503('注册失败!');
        }
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
                    break;
                }
            default:
                {
                    $validate = null;

                }
        }

        return $validate;
    }

    /**
     * @param $string
     * @return bool|string
     */
    public function encryption($string)
    {
        return password_hash($string, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    /**
     * @param $value
     * @param $hashedValue
     * @return bool
     */
    public function verifyPassword($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }

}
