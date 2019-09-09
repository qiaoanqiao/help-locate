<?php


namespace App\RequestValidate;


interface RequestValidateInterface
{

    /**
     * @return mixed
     */
    public function authorize();

    public function rules();


}