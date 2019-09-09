<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Lib\Pool\MysqlPool;
use App\Process\HotReload;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\EasySwoole\Config as GConfig;
use EasySwoole\Mysqli\Config;

class EasySwooleEvent implements Event
{

    /**
     * 框架初始化事件
     * https://www.easyswoole.com/Cn/Event/initialize.html
     */
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        PoolManager::getInstance()->register(MysqlPool::class);
        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        //自动重载
        $autoReloadBool = $instance->getConf('AUTO_RELOAD');
        if($autoReloadBool === true) {
            $swooleServer = ServerManager::getInstance()->getSwooleServer();
            $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        }

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}