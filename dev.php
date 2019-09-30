<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 1,
            'task_worker_num' => 1,
            'reload_async' => true,
            'task_enable_coroutine' => true,
            'max_wait_time'=>3
        ],
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    'AUTO_RELOAD' => true,
    'MONGODB' => [
        'dsn' => 'mongodb://127.0.0.1:27017',
        'database'=> 'gps',
        'POOL_MAX_NUM' => 8,
        'POOL_TIME_OUT' => 0.1,
        'timeout' => 5,
    ],
    'MYSQL'         => [
        'host'                 => '127.0.0.1',
        'port'                 => 3306,
        'user'                 => 'gps',
        'password'             => 'jbL8PbhXZWhdNyaz',
        'database'             => 'gps',
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
        'host'          => '127.0.0.1',
        'port'          => '6379',
        'auth'          => 'dc853636a2413f2d',
        'db'            => 0,//选择数据库,默认为0
        //pool
        'maxIdleTime'          => 1,//最大存活时间,超出则会每$intervalCheckTime/1000秒被释放
        'maxObjectNum'         => 20,//最大创建数量
        'minObjectNum'         => 5,//最小创建数量 最小创建数量不能大于等于最大创建
        'intervalCheckTime' => 1000,
        'getObjectTimeout' => 3.0,
        'extraConf' => [],
    ],
    'CONFIGS_DIR' => RUNNING_ROOT . '/App/Config',

];
