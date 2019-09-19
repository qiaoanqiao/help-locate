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
        $config = \EasySwoole\EasySwoole\Config::getInstance();

        $mysqlPoolConf = PoolManager::getInstance()->register(MysqlPool::class);
        $mysqlConf = $config->getConf('mysql_pool');
        $mysqlPoolConf->setExtraConf($mysqlConf['extraConf'])
            ->setGetObjectTimeout($mysqlConf['getObjectTimeout'])
            ->setIntervalCheckTime($mysqlConf['intervalCheck'])
            ->setMaxIdleTime($mysqlConf['maxIdleTime'])
            ->setMaxObjectNum($mysqlConf['maxObjectNum'])
            ->setMinObjectNum($mysqlConf['minObjectNum']);

        $redisPoolConf = PoolManager::getInstance()->register(RedisPool::class);
        $redisConf = $config->getConf('redis_pool');

        $redisPoolConf->setExtraConf($redisConf['extraConf'])
            ->setGetObjectTimeout($redisConf['getObjectTimeout'])
            ->setIntervalCheckTime($redisConf['intervalCheck'])
            ->setMaxIdleTime($redisConf['maxIdleTime'])
            ->setMaxObjectNum($redisConf['maxObjectNum'])
            ->setMinObjectNum($redisConf['minObjectNum']);

        //代码修改自动重载
        if(isDebug()) {
            $autoReloadBool = $config->getConf('AUTO_RELOAD');
            if($autoReloadBool === true) {
                $swooleServer = ServerManager::getInstance()->getSwooleServer();
                $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
            }
        }
        self::ipList();
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        $redis = \App\Lib\Redis\Redis::getInstance();
        $redis->close();
        unset($redis);
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