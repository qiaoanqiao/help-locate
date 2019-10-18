<?php

namespace App\HttpController;

use App\Common\BaseController;
use App\Models\Pool\Mysql\Relation;
use EasySwoole\Validate\Validate;

/**
 * Class Index
 * @package App\HttpController
 */
class UserRelation extends BaseController
{
    use \App\Common\UserAuthTrait;

    public function index()
    {
        $relationModel = new Relation();
        $relationData = $relationModel->userRelation($this->id());

        return $this->success200();
    }

    public function list()
    {

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
