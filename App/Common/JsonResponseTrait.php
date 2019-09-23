<?php
namespace App\Common;

trait JsonResponseTrait
{
    /**
     * 请求成功
     * @param string $message
     * @param array $data
     * @param int $code
     * @return mixed
     */
    public function success200($message = '请求成功!', $data = [], $code = 200)
    {
        return $this->writeJson($code, $data, $message);
    }

    public function error404($message = '页面或接口不存在!', $data = [], $code = 200)
    {
        return $this->writeJson($code, $data, $message);
    }

    /**
     * 用于抛出500系统错误
     *
     */
    public function error500($message = '系统出现异常!',  $data = [], $code = 500)
    {
        return $this->writeJson($code, $data, $message);
    }

    /**
     * 用于抛出403 拒绝访问(没有权限)错误
     *
     * @param  null  $message
     */
    public function error403($message = '拒绝访问!没有权限!', $data = [], $code = 403)
    {
        return $this->writeJson($code, $data, $message);
    }

    /**
     * 用于抛出522 表单项验证错误
     *
     * @param  null  $message
     */
    public function error522($message = '请检查表单项', $errors = [], $code = 522)
    {
        return $this->writeJson($code, $errors, $message);
    }

    /**
     * 用于抛出503用户请求不符合系统业务逻辑要求
     *
     * @param  null  $message
     */
    public function error503($message = '请检查请求信息是否符合要求!', $data = [], $code = 503)
    {
        return $this->writeJson($code, $data, $message);
    }

    /**
     * 用于抛出401 未授权(未登录) 错误
     *
     * @param  null  $message
     */
    public function error401($message = '您没有进行登录!', $data = [], $code = 401)
    {
        return $this->writeJson($code, $data, $message);
    }

}