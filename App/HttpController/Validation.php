<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\RequestValidate\UserLoginRequest;
use App\RequestValidate\UserRegisterRequest;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Validate\Validate;
use EasySwoole\VerifyCode\Conf as GraphicsConf;

/**
 * Class Index
 * @package App\HttpController
 */
class Validation extends BaseController
{
    function getImage()
    {
        $config = new GraphicsConf();
        $config = $config->setUseCurve();
        $code = new \EasySwoole\VerifyCode\VerifyCode($config);
        $this->response()->withHeader('Content-Type','image/png');
        return $this->response()->write($code->DrawCode()->getImageByte());
    }

    public function getBase64()
    {
        $params = $this->requestParam();
        $config = new GraphicsConf();
        $config = $config->setUseCurve();

        $validationKeyConf = Config::getInstance()->getConf('validation_scenarios');
        if(!isset($validationKeyConf[$params['validation_scenarios']])) {
            return $this->error522('没有此验证场景!');
        }

        $code = new \EasySwoole\VerifyCode\VerifyCode($config);

        return $this->success200('图片获取成功!', [
            'image' => $code->DrawCode()->getImageBase64(),
            'captcha_image_key' => random(15) . $params['validation_scenarios']
        ]);
    }

    public function graphicsBase64()
    {

    }

    public function valdateGraphics()
    {

    }

    public function smsCode()
    {

    }

    public function valdateSmsCode()
    {

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
                }
            default:
                {
                    $validate = null;
                }
        }

        return $validate;
    }

}
