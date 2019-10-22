<?php
return [
    'AUTO_RELOAD' => true,
    'MONGODB' => [
        'dsn' => 'mongodb://127.0.0.1:27017',
        'database'=> 'gps',
        'POOL_MAX_NUM' => 8,
        'POOL_TIME_OUT' => 0.1,
        'timeout' => 5,
    ],
    'MYSQL'         => [
        'host'                 => env('MYSQL_HOST','127.0.0.1'),
        'port'                 => env('MYSQL_PORT', 3306),
        'user'                 => env('MYSQL_USERNAME','gps'),
        'password'             => env('MYSQL_PASSWORD','PASSWORD'),
        'database'             => env('MYSQL_DATABASE','gps'),
        'timeout'              => 30,
        'charset'              => 'utf8mb4',
        'connect_timeout'      => '5',//连接超时时间
        'POOL_TIME_OUT'        => 0.1,
        //pool
        //pool
        'maxIdleTime'          => 1,//最大存活时间,超出则会每$intervalCheckTime/1000秒被释放
        'maxObjectNum'         => 20,//最大创建数量
        'minObjectNum'         => 5,//最小创建数量 最小创建数量不能大于等于最大创建
        'intervalCheckTime' => 1000,
        'getObjectTimeout' => 3.0,
        'extraConf' => [],
    ],
    /*################ REDIS CONFIG ##################*/
    'REDIS' => [
        'host'          => env('REDIS_HOST', '127.0.0.1'),
        'port'          => env('REDIS_PORT','6379'),
        'auth'          => env('REDIS_AUTH',null),
        'db'            => 0,//选择数据库,默认为0
        //pool
        'maxIdleTime'          => 1,//最大存活时间,超出则会每$intervalCheckTime/1000秒被释放
        'maxObjectNum'         => 20,//最大创建数量
        'minObjectNum'         => 5,//最小创建数量 最小创建数量不能大于等于最大创建
        'intervalCheckTime' => 1000,
        'getObjectTimeout' => 3.0,
        'extraConf' => [],
    ],

];
