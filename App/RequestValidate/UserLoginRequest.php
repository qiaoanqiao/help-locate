<?php


namespace App\RequestValidate;


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
            'phone:手机号' => 'required|length:11,长度错误'
        ];
    }
}