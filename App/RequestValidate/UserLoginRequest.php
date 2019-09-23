<?php


namespace App\RequestValidate;


use App\Common\BaseRequestValidate;

class UserLoginRequest extends BaseRequestValidate
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mobile:手机号' => ['required', 'lengthMin' => [11, '长度错误'], 'regex' => '/1(3[0-9]|4[579]|5[0-35-9]|7[0135-8]|8[0-9])[0-9]{8}$/'],
            'captcha_image_code:手机验证码' => 'required|lengthMin:4,长度错误',
            'captcha_image_key:短信验证码 key' => 'required',
            'password:密码' => 'required|lengthMin:8,长度错误',
            'validation_scenarios:验证场景' => 'required',
        ];
    }
}