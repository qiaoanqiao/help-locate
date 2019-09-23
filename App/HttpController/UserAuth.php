<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Lib\AuthToken;
use App\Models\Pool\Mysql\User;
use App\RequestValidate\UserLoginRequest;
use App\RequestValidate\UserRegisterRequest;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Validate\Validate;
use SuperClosure\Analyzer\Token;

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
        $captcha_sms_key = $this->requestParam('captcha_sms_key');
        $captcha_code = $this->requestParam('captcha_sms_code');
        $mobile = $this->requestParam('mobile');
        $password = $this->requestParam('password');

        $validation = new Validation();
        if(($valMessage = $validation->verifyMobileVerificationCode($validation_scenarios, $captcha_sms_key, $captcha_code, $mobile)) !== true) {
            return $this->error522($valMessage);
        }

        $user = new User();
        if($user->hasWhetherUserOnly($mobile)) {
            return $this->error522('您的手机号已被注册!', ['mobile' => '您的手机号已被注册!']);
        }
        $passwordHash = $this->encryption($password);
        $insertStatus = $user->mobileCreateUser($mobile, $passwordHash);

        if($insertStatus) {
            return $this->success200('注册成功!');
        } else {
            return $this->error503('注册失败!');
        }
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function login()
    {
        $validation_scenarios = $this->requestParam('validation_scenarios');
        $captcha_image_key = $this->requestParam('captcha_image_key');
        $captcha_code = $this->requestParam('captcha_image_code');
        $mobile = $this->requestParam('mobile');
        $password = $this->requestParam('password');

        //图形验证码是否正确
        if (!(new Validation())->validateGraphicsCode($captcha_image_key, $validation_scenarios, $captcha_code)) {
            return $this->error522('图形验证码输入错误!', ['captcha_code' => '图形验证码输入错误!']);
        }

        if(empty($client = ($this->request()->getHeader('client'))[0] ?? '')) {
            return $this->error522('未知客户端请求!', ['client' => '客户端不正确!!']);
        };

        $tokenClientConf = Config::getInstance()->getConf('token_client');
        if(!isset($tokenClientConf[$client])) {
            return $this->error403('不存在的客户端!', ['client' => '客户端不正确!!']);
        }

        $user = new User();
        if(($userData = $user->login($mobile)) === null) {
            return $this->error522('您的手机号未注册!', ['mobile' => '您的手机号未注册!']);
        }
        //todo 错误次数频率限制
        if(!$this->verifyPassword($password, $userData['password'])) {
            return $this->error522('密码不正确!', ['password' => '密码不正确!']);
        }
        $authToken =  new AuthToken();
        $token = $authToken->generateToken($client, $userData);

        return $this->success200('登录成功!', [
            'user' => $userData,
            'token' => $token,
        ]);

    }


    /**
     * @param string|null $action
     * @return Validate|null
     */
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
        return password_hash($string, PASSWORD_BCRYPT);
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
