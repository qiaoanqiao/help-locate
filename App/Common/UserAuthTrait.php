<?php


namespace App\Common;


use App\Lib\AuthToken;
use App\Models\Pool\Mysql\User;
use EasySwoole\EasySwoole\Config;

/**
 * 放置在需要token 验证的请求控制器中
 *
 * Trait UserAuth
 * @package App\Common
 */
trait UserAuth
{
    public $user;

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function leadMiddleware()
    {
        if(empty($token = ($this->request()->getHeader('token'))[0] ?? '')) {
            return $this->error401('当前接口需要登录!', ['token' => '没有客户凭证!']);
        };
        if(empty($client = ($this->request()->getHeader('client'))[0] ?? '')) {
            return $this->error522('未知客户端请求!', ['client' => '客户端不正确!!']);
        };
        $tokenClientConf = Config::getInstance()->getConf('token_client');
        if(!isset($tokenClientConf[$client])) {
            return $this->error403('不存在的客户端!', ['client' => '客户端不正确!!']);
        }
        $authToken =  new AuthToken();
        if(is_string($data = $authToken->getTokenAsData($client, $token))) {
            return $this->error401($data);
        }

        $this->user = $data;
    }

    /**
     * 缓存的用户信息(不需要及时更新)
     */
    public function userCache()
    {
        return $this->user ?? [];
    }

    /**
     * 最新的用户信息(需要及时更新)
     */
    public function user()
    {
        return (new User())->find($this->user['id']);
    }

    public function id()
    {
        return $this->user['id'] ?? 0;
    }
}