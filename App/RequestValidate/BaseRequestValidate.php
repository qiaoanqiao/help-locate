<?php


namespace App\RequestValidate;


use EasySwoole\Http\Request;
use EasySwoole\Validate\Validate;

class BaseRequestValidate implements RequestValidateInterface
{
    protected $valObj;
    public $validate;

    /**
     * BaseRequestValidate constructor.
     * @param Validate $validate
     */
    public function __construct(Validate $validate)
    {
        $this->validate = $validate;
        foreach ($this->rules() as $key => $value) {
            $columnArr = explode(":", $key);
            $method = $this->validate->addColumn($columnArr[0], $columnArr[1]);
            $this->methodAdd($method, explode("|", $value));
        }

        $this->validate = $validate;
    }

    public function getValObj()
    {
        return $this->validate;
    }


    public function methodAdd($methodCall, array $rules)
    {
        foreach ($rules as $ruleStr) {

            $methods = explode(":", $ruleStr);
            if(count($methods) > 1) {
                $args = explode(",", $methods[1]);
                $method = $methods[0];
                $methodCall = $methodCall->$method(... $args);
            } else {
                $method = $methods[0];
                $methodCall = $methodCall->$method();
            }

        }
        return $methodCall;
    }
    /**
     * @return mixed
     */
    public function authorize()
    {
        // TODO: Implement authorize() method.
    }

    public function rules()
    {
        return [];
    }
}