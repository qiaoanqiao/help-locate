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

    public function run(int $taskId, int $workerIndex)
    {
        IpList::getInstance()->clear();
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        // TODO: Implement onException() method.
    }
}
