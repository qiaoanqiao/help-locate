<?php


namespace App\Common;


interface RequestValidateInterface
{

    /**
     * @return mixed
     */
    public function authorize();

    public function rules();


}