<?php
return [
    'token_client' => [
        'default' => [
            'ttl' =>  86400,
            'length' => 30,
        ],
        //非常规手机端(小程序等)
        'applet' => [
            'ttl' =>  86400,
            'length' => 30,
        ],
        //小程序
        'ios' => [
            'ttl' =>  86400,
            'length' => 30,
        ],
        //小程序
        'android' => [
            'ttl' =>  86400,
            'length' => 30,
        ],
    ]

];