<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Lib\AuthToken;
use App\Models\Pool\Mysql\User;
use App\ModelTransform\UserTransform;
use EasySwoole\Validate\Validate;

/**
 * Class Index
 * @package App\HttpController
 */
class UserInformation extends BaseController
{
    use \App\Common\UserAuthTrait;

    public function index()
    {
        return $this->success200();
    }

    public function personalCenter()
    {
        $userData = $this->user();
        if(isDebug()) {
            return $this->success200('获取成功!', array_merge((new UserTransform())->personalCenter($userData), ['token' => $this->token]));
        }
        return $this->success200('获取成功!', (new UserTransform())->personalCenter($userData));

    }


    /**
     * @param string|null $action
     * @return Validate|null
     */
    protected function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action) {
            case 'login':
                {
                    break;
                }
            case 'register':
                {
                    break;
                }
            default:
                {
                    $validate = null;

                }
        }

        return $validate;
    }



}
