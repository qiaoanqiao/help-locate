<?php
return [
    'redis_pool' => [
        'intervalCheck' => 1000,
        'maxIdleTime' => 1,
        'maxObjectNum' => 20,
        'minObjectNum' => 5,
        'getObjectTimeout' => 3.0,
        'extraConf' => [],
    ],
    'mysql_pool' => [
        'intervalCheck' => 30000,
        'maxIdleTime' => 15,
        'maxObjectNum' => 20,
        'minObjectNum' => 5,
        'getObjectTimeout' => 3.0,
        'extraConf' => [],
    ],

];