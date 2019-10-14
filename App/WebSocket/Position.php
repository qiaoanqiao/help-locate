<?php


namespace App\WebSocket;


use App\Common\UserAuthWebSocketTrait;
use App\Common\WebSocketJsonResponseTrait;
use App\Models\Pool\Mysql\UserLocation;
use EasySwoole\Socket\AbstractInterface\Controller;

class Position extends Controller
{
    use WebSocketJsonResponseTrait, UserAuthWebSocketTrait;

    public function background()
    {
        $this->leadMiddleware();
        $data = $this->caller()->getArgs();
        /**
        array(6) {
        ["token"]=>
        string(30) "sk9iR23zQZ80KMjaBSX57f4g6NeFvm"
        ["latitude"]=>
        float(29.8172)
        ["longitude"]=>
        float(121.547)
        ["speed"]=>
        int(-1)
        ["accuracy"]=>
        int(65)
        ["locate_mode"]=>
        string(8) "wifi,gps"
        }
         */
        $data['user_id'] = $this->id();
        $model = new UserLocation();
        $model->create($data);

        return $this->success200();
    }

    public function get()
    {
        echo 'get进来了';
        return $this->success200();
    }
}
