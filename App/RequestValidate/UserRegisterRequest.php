<?php


namespace App\RequestValidate;


use App\Common\BaseRequestValidate;

class UserRegisterRequest extends BaseRequestValidate
{
    public function rules()
    {
        return [
            'mobile:手机号' => ['required', 'length' => [11, '长度错误'], 'regex' => '/1(3[0-9]|4[579]|5[0-35-9]|7[0135-8]|8[0-9])[0-9]{8}$/'],
            'sms_code:手机验证码' => 'required|length:4,长度错误',
            'verification_key:短信验证码 key' => 'required',
        ];
    }
}