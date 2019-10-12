<?php


namespace App\WebSocket;


use App\Common\WebSocketJsonResponseTrait;
use EasySwoole\Socket\AbstractInterface\Controller;

class Position extends Controller
{
    use WebSocketJsonResponseTrait;

    public function background()
    {
        echo 'background进来了' . PHP_EOL;
        return $this->success200();
    }

    public function get()
    {
        echo 'get进来了';
        return $this->success200();
    }
}
