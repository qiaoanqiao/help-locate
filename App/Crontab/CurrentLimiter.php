<?php


namespace App\Crontab;

use App\Lib\IpList;
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class CurrentLimiter extends AbstractCronTask
{

    public static function getRule(): string
    {
        // 定时周期 （每两分钟一次）
        return '*/1 * * * *';
    }

    public static function getTaskName(): string
    {
        // 定时任务名称
        return 'CurrentLimiter';
    }

    static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
    {
        IpList::getInstance()->clear();
    }
}