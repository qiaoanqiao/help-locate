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
use App\WebSocket\WebSocketParser;
use Dotenv\Dotenv;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\EasySwoole\Config as GConfig;
use App\Lib\Pool\RedisPool;
use EasySwoole\Socket\Dispatcher;
use App\WebSocket\WebSocketEvent;

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

    /**
     * @param EventRegister $register
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \EasySwoole\Component\Pool\Exception\PoolObjectNumError
     * @throws \EasySwoole\Socket\Exception\Exception
     * @throws \Exception
     */
    public static function mainServerCreate(EventRegister $register)
    {
        /**
         * **************** websocket控制器 **********************
         */
        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();
        // 设置 Dispatcher 为 WebSocket 模式
        $conf->setType(\EasySwoole\Socket\Config::WEB_SOCKET);
        // 设置解析器对象
        $conf->setParser(new WebSocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);

        // 给server 注册相关事件 在 WebSocket 模式下  on message 事件必须注册 并且交给 Dispatcher 对象处理
        $register->set(EventRegister::onMessage, function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) use ($dispatch) {
            $dispatch->dispatch($server, $frame->data, $frame);
        });

        //自定义握手事件
        $websocketEvent = new WebSocketEvent();
        $register->set(EventRegister::onHandShake, function (\swoole_http_request $request, \swoole_http_response $response) use ($websocketEvent) {
            $websocketEvent->onHandShake($request, $response);
        });

        //自定义关闭事件
        $register->set(EventRegister::onClose, function (\swoole_server $server, int $fd, int $reactorId) use ($websocketEvent) {
            $websocketEvent->onClose($server, $fd, $reactorId);
        });


        /**
         * **************** redis&mysql 连接池 **********************
         */
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

        /**
         * **************** 代码修改自动重载 **********************
         */
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