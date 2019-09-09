<?php
namespace App\Common;

trait JsonResponseTrait
{
    public function success200($message = '请求成功!', $data = [], $code = 200)
    {
        return $this->writeJson(['status'  => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    public function error404($message = '页面或接口不存在!', $data = [], $code = 200)
    {
        return $this->writeJson(['status'  => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    /**
     * 用于抛出500系统错误
     *
     */
    public function error500($message = '系统出现异常!',  $data = [], $code = 500)
    {
        return $this->writeJson(['status'  => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    /**
     * 用于抛出401 用户权限 错误
     *
     * @param  null  $message
     */
    public function error403($message = '没有权限!', $data = [], $code = 403)
    {
        return $this->writeJson([
            'status'  => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    /**
     * 用于抛出522 表单项验证错误
     *
     * @param  null  $message
     */
    public function error522($message = '请检查表单项', $errors = [], $code = 522)
    {
        return $this->writeJson([
            'status'  => $code,
            'message' => $message,
            'data'    => $errors,
        ]);
    }

    /**
     * 用于抛出403用户未登录造成的错误
     *
     * @param  null  $message
     */
    public function error503($message = '请检查请求信息是否符合要求!', $data = [], $code = 503)
    {
        return $this->writeJson(['status'  => $code,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    /**
     * 用于抛出401 用户权限 错误
     *
     * @param  null  $message
     */
    public function error401($message = ' 您没有权限进行此次操作!', $data = [], $code = 401)
    {
        return $this->writeJson([
            'status'  => $code,
            'message' => $message,
            'data'    => [],
        ]);
    }
}