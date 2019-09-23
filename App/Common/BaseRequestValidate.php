<?php


namespace App\Common;


use EasySwoole\Validate\Validate;

class BaseRequestValidate implements RequestValidateInterface
{
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
            if(is_array($value)) {
                $this->arrayMethodAdd($method, $value);
            } else {
                $this->methodAdd($method, explode("|", $value));
            }

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
     * 数组规则
     * @param $methodCall
     * @param $rules
     * @return mixed
     */
    public function arrayMethodAdd($methodCall, $rules)
    {
        foreach ($rules as $method => $rule) {
            if(is_array($rule)) {
                $methodCall = $methodCall->$method(... $rule);
            } else {
                $args = explode(",", $rule);
                //索引
                if(is_numeric($method)) {
                    $method = $args[0];
                    $methodCall = $methodCall->$method();
                } else {
                    $methodCall = $methodCall->$method(... $args);
                }
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