<?php


namespace App\Lib;


use EasySwoole\Component\Singleton;
use EasySwoole\Component\TableManager;
use Swoole\Table;

class IpList
{
    use Singleton;

    /** @var Table */
    protected $table;

    public  function __construct()
    {
        TableManager::getInstance()->add('ipList', [
            'ip' => [
                'type' => Table::TYPE_STRING,
                'size' => 16
            ],
            'count' => [
                'type' => Table::TYPE_INT,
                'size' => 8
            ],
            'firstAccessTime' => [
                'type' => Table::TYPE_INT,
                'size' => 8
            ]
        ], 1024*128);
        $this->table = TableManager::getInstance()->get('ipList');
    }

    function access(string $ip, $uri):int
    {
        $key  = substr(md5($ip . $uri), 8,16);
        $info = $this->table->get($key);

        if ($info) {
            $this->table->set($key, [
//                'firstAccessTime' => time(),
                'count'          => $info['count'] + 1,
            ]);
            return $info['count'] + 1;
        }else{
            $this->table->set($key, [
                'ip'             => $ip,
                'firstAccessTime' => time(),
                'count'          => $info['count'] + 1,
            ]);
            return 1;
        }
    }

    function clearLine(string $ip, $uri)
    {
        $key  = substr(md5($ip . $uri), 8,16);
        $this->table->del($key);
    }

    function clear()
    {
        $thisTime = time();
        foreach ($this->table as $key => $item){
            if(($thisTime - $item['firstAccessTime']) >= 60) {
                $this->table->del($key);
            }
        }
    }

    function accessList($count = 10):array
    {
        $ret = [];
        foreach ($this->table as $key => $item){
            if ($item['count'] >= $count){
                $ret[] = $item;
            }
        }
        return $ret;
    }
}