<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Crontab\CurrentLimiter;
use App\Lib\IpList;
use App\Lib\Pool\MysqlPool;
use App\Process\HotReload;
use Dotenv\Dotenv;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\EasySwoole\Config as GConfig;
use App\Lib\Pool\RedisPool;

class EasySwooleEvent implements Event
{

    /**
     * 框架初始化事件
     * https://www.easyswoole.com/Cn/Event/initialize.html
     */
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        self::loadConfigs();
    }

    public static function mainServerCreate(EventRegister $register)
    {
        PoolManager::getInstance()->register(MysqlPool::class);
        PoolManager::getInstance()->register(RedisPool::class);

        $instance = \EasySwoole\EasySwoole\Config::getInstance();
        //自动重载
        $autoReloadBool = $instance->getConf('AUTO_RELOAD');
        if($autoReloadBool === true) {
            $swooleServer = ServerManager::getInstance()->getSwooleServer();
            $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        }
        self::ipList();
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }

    /**
     * 加载配置文件
     * @param $dir
     */
    public static function loadConfigs()
    {

        $config = GConfig::getInstance();
        $dir = $config->getConf('CONFIGS_DIR');
        //加载 env 文件
        $dotenv = Dotenv::create(RUNNING_ROOT, '.env');
        $dotenv->load();

        $configs = [];
        foreach( glob( "{$dir}/*.php" ) as $filename ) {
            $configs = array_merge($configs, require ($filename));
        }

        if(!empty($configs)) {
            $config->merge($configs);
        }

        unset($configs, $dir, $dotenv);
    }

    public static function ipList()
    {
        // 开启IP限流
        IpList::getInstance();
        Crontab::getInstance()->addTask(CurrentLimiter::class);
    }

}