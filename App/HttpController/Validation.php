<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Lib\IpList;
use App\Lib\Pool\RedisPool;
use App\Lib\Redis\Redis;
use App\RequestValidate\UserLoginRequest;
use App\RequestValidate\UserRegisterRequest;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Validate\Validate;
use EasySwoole\VerifyCode\Conf as GraphicsConf;

/**
 * Class Index
 * @package App\HttpController
 */
class Validation extends BaseController
{
    public $graphicsKey = 'graphics';
    public $smsKey = 'sms';
    public $redisPool = null;

    public $validationKeyConf = [];

    public function __construct()
    {
        parent::__construct();

        $this->validationKeyConf = Config::getInstance()->getConf('validation_scenarios');
    }

    public function getValidationKeyConf(): array
    {
        if (empty($this->validationKeyConf)) {
            return $this->validationKeyConf = Config::getInstance()->getConf('validation_scenarios');
        } else {
            return $this->validationKeyConf;
        }
    }

    /**
     * 获取Base64格式图形验证码
     *
     * @return bool
     */
    public function getGraphicsBase64()
    {
        $config = new GraphicsConf();
        $config = $config->setUseCurve();

        if (!empty($this->validationScenarios($this->requestParam('validation_scenarios')))) {
            return true;
        }

        $code = new \EasySwoole\VerifyCode\VerifyCode($config);
        $generateVerificationCode = $code->DrawCode();
        $valCode = $generateVerificationCode->getImageCode();
        $valKey = randomScenariosKey(12);
        $cacheKey = $this->assemblyCacheKey($valKey, $this->requestParam('validation_scenarios'), $this->graphicsKey);

        $this->setValCodeCache($cacheKey, $valCode, $this->getValidationKeyConf()[$this->requestParam('validation_scenarios')][$this->graphicsKey]['ttl']);
        if (isDebug()) {
            return $this->success200('图片获取成功!', [
                'image' => $generateVerificationCode->getImageBase64(),
                'captcha_image_key' => $valKey,
                'captcha_image_code' => $valCode,
            ]);
        } else {
            return $this->success200('图片获取成功!', [
                'image' => $generateVerificationCode->getImageBase64(),
                'captcha_image_key' => $valKey,
            ]);
        }
    }


    /**
     * 发送短信验证码
     *
     * @return mixed
     */
    public function sendSmsCode()
    {
        $validation_scenarios = $this->requestParam('validation_scenarios');
        $captcha_image_key = $this->requestParam('captcha_image_key');
        $captcha_code = $this->requestParam('captcha_image_code');
        $mobile = $this->requestParam('mobile');
        $authType = $this->smsKey;


        //验证场景是否存在
        if (!empty($this->validationScenarios($this->requestParam('validation_scenarios')))) {
            return true;
        }
        //图形验证码是否正确
        if (!$this->validateGraphicsCode($captcha_image_key, $validation_scenarios, $captcha_code)) {
            return $this->error522('图形验证码输入错误!', ['captcha_code' => '图形验证码输入错误!']);
        }

        //发送短信流程频率限制
        $ttl = $this->getValidationKeyConf()[$this->requestParam('validation_scenarios')][$authType]['ttl'];
        if(!isDebug()) {
            if (($checkThrottling = $this->checkIpThrottling($validation_scenarios, $authType, $ttl)) !== true) {
                return $this->error503('您获取验证码次数已超过限制请' . $checkThrottling . '秒后重试!', ['captcha_code' => '频率过快!']);
            }
        }

        $valKey = randomScenariosKey(12);
        $cacheKey = $this->assemblyCacheKey($valKey, $validation_scenarios, $authType);
        $valCode = \EasySwoole\Utility\Random::number(5);

        //设置验证业务逻辑验证
        $this->setValCodeCache($cacheKey, $valCode . ',' . $mobile, $ttl);
        //设置限制频率逻辑验证
        $this->setIpThrottling($validation_scenarios, $authType, $ttl);

        if (isDebug()) {
            return $this->success200('获取短信验证码成功!', ['captcha_sms_key' => $valKey, 'captcha_sms_code' => $valCode]);
        } else {
            return $this->success200('获取短信验证码成功!', ['captcha_sms_key' => $valKey]);
        }

    }


    /**
     * 验证手机验证码
     */
    public function verifyMobileVerificationCode($validation_scenarios, $captcha_image_key, $captcha_code, $mobile)
    {
        if(isDebug()) {
            return true;
        }
        $authType = $this->smsKey;

        $cacheKey = $this->assemblyCacheKey($captcha_image_key, $validation_scenarios, $authType);
        $data = $this->getValCodeCache($cacheKey);
        if (empty($data)) {

            return '发送的验证码已过期!请重新发送!';
        }
        $data = explode(",", $data);
        if (isset($data[0]) && !empty($data[0]) && isset($data[1]) && !empty($data[1])) {
            if (!string_coomp($data[1], $mobile)) {

                return '手机号不正确!';
            }
            if (!string_coomp(strtolower($data[0]), strtolower($captcha_code))) {
                return '短信验证码不正确';
            }

            return true;
        } else {

            return '发送的验证码出现问题!手机号和验证码为空!';
        }

    }


    /**
     * 设置验证 ip 限制频率
     * @param string $ip ip 用户 ip
     * @param string $authType 验证类型(短信, 图形等)
     */
    public function setIpThrottling(string $validation_scenarios, string $authType, $ttl = 60)
    {
        $fd = $this->request()->getSwooleRequest()->fd;
        $ip = ServerManager::getInstance()->getSwooleServer()->getClientInfo($fd)['remote_ip'];

        $key = $ip . '_' . $validation_scenarios . '_' . $authType;

        $this->setValCodeCache($key, time(), $ttl);
    }

    /**
     * 验证 ip和验证场景 是否超过限制
     * @param string $validation_scenarios
     * @param string $authType
     * @return bool
     */
    public function checkIpThrottling(string $validation_scenarios, string $authType, $ttl = 60)
    {
        $fd = $this->request()->getSwooleRequest()->fd;
        $ip = ServerManager::getInstance()->getSwooleServer()->getClientInfo($fd)['remote_ip'];

        $key = $ip . '_' . $validation_scenarios . '_' . $authType;

        $data = $this->getValCodeCache($key);

        if (!empty($data)) {
            return ($ttl - (time() - $data));
        }

        return true;
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
            case 'getGraphicsBase64':
                {
                    $v->addColumn('validation_scenarios', '验证场景')->required('不能为空!');
                    $validate = $v;
                    break;
                }
            case 'sendSmsCode':
                {
                    $v->addColumn('validation_scenarios', '验证场景')->required('不能为空!');
                    $v->addColumn('captcha_image_key', '图形验证码 Key')->required('不能为空!');
                    $v->addColumn('captcha_image_code', '图形验证码')->required('不能为空!');
                    $validate = $v;
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
     * 组装验证码插入到缓存中的 key
     *
     * @param string $randomKey 随机 key
     * @param string $validation_scenarios 验证场景
     * @param string $autype 验证类型
     * @return string
     */
    public function assemblyCacheKey(string $randomKey, string $validation_scenarios, string $autype)
    {
        return $randomKey . '_' . $validation_scenarios . '_' . $autype;
    }

    /**
     * 验证图形验证码
     *
     * @param string $captcha_image_key
     * @param string $validation_scenarios
     * @param string $code
     * @return bool
     */
    public function validateGraphicsCode(string $captcha_image_key, string $validation_scenarios, string $code): bool
    {
        $cacheKey = $this->assemblyCacheKey($captcha_image_key, $validation_scenarios, $this->graphicsKey);

        if(isDebug()) {
            return true;
        }
        return string_coomp(strtolower($this->getValCodeCache($cacheKey)), strtolower($code));

    }

    /**
     * 验证场景验证
     *
     * @param $scenarios
     * @return mixed
     */
    public function validationScenarios($scenarios)
    {
//        var_dump($scenarios, $this->getValidationKeyConf());
        if (!isset($this->getValidationKeyConf()[$scenarios])) {
            return $this->error522('没有此验证场景!');
        }
    }

    /**
     * 随机生成不同验证类型和场景的缓存 Key
     *
     * @param string $scenario
     * @param string $autype
     * @return string
     */
    public function randomScenariosKey()
    {
        return \EasySwoole\Utility\Random::character(12);
    }

    /**
     * 进行验证码缓存
     *
     * @param $key
     * @param $code
     */
    public function setValCodeCache($key, $code, $ttl)
    {
        $this->getCachePool()->set($key, $code, $ttl);
    }

    /**
     * @param string $key
     * @return string
     */
    public function getValCodeCache(string $key = '')
    {
        $data = $this->getCachePool()->get($key);

        return $data ?: '';
    }

    public function closeCache()
    {
        if(!empty($this->redisPool)){
            if($this->redisPool instanceof \App\Lib\Redis\Redis) {
                $this->redisPool->close();
            } else {
                PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redisPool);
            }
        }
    }

}
