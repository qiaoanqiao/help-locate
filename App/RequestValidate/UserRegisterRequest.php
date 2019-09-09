<?php


namespace App\RequestValidate;


use App\Common\BaseRequestValidate;

class UserRegisterRequest extends BaseRequestValidate
{
    public function rules()
    {
        return [
            'phone:手机号' => 'required|length:11,长度错误',
            'sms_code:手机验证码' => 'required|length:4,长度错误',
            'verification_key:短信验证码 key' => 'required',
        ];
    }
}