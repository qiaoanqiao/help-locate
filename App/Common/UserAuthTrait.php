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
trait UserAuthTrait
{
    public $user = [];
    public $token = '';

    public function getAuthData($key)
    {
        return ($this->request()->getHeader($key))[0] ?? '';
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function leadMiddleware()
    {
        if(empty($client = $this->getAuthData('client'))) {
            if(isDebug()) {
                $client = 'android';
            } else {
                return $this->error522('未知客户端请求!', ['client' => '客户端不正确!!']);
            }
        };
        $authToken =  new AuthToken();
        if(empty($token = $this->getAuthData('user_token'))) {
            if(isDebug()) {
                $userData = (new User())->login(config('debug_mobile'));
                $token = $authToken->generateToken($client, $userData);
            } else {
                return $this->error401('当前接口需要登录!', ['token' => '没有客户凭证!']);
            }
        };
        $tokenClientConf = Config::getInstance()->getConf('token_client');
        if(!isset($tokenClientConf[$client])) {
            return $this->error403('不存在的客户端!', ['client' => '客户端不正确!!']);
        }

        if(is_string($data = $authToken->getTokenAsData($client, $token))) {
            return $this->error401($data);
        }
        $this->token = $token;
        $this->user = $data;

        return true;
    }

    public function setUserData()
    {

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
        $user = (new User())->find($this->user['id']);

        return $user;
    }

    public function id()
    {
        return $this->user['id'] ?? 0;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        if(empty($this->user)) {
            $this->leadMiddleware();
        }

        return $this->user;
    }
}
