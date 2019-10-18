<?php
namespace App\Models\Pool\Mysql;


use App\Models\Pool\Mysql\Base;
use EasySwoole\Mysqli\Exceptions\ConnectFail;
use EasySwoole\Mysqli\Exceptions\PrepareQueryFail;

/** @ODM\Document */
class Relation extends Base
{
    public $tableName = "relations";

    /**
     * @param $data
     * @throws ConnectFail
     * @throws PrepareQueryFail
     * @throws \Throwable
     */
    public function create($data)
    {
        $locationData = [
            'user_id' => $data['user_id'] ?? 0,
            'locate_mode' => $data['locate_mode'] ?? '',
            'latitude' => $data['latitude'] ?? '',
            'longitude' => $data['longitude'] ?? '',
            'accuracy' => $data['accuracy'] ?? '',
            'verticalAccuracy' => $data['verticalAccuracy'] ?? '',
            'type' => $data['type'] ?? 'gcj02',
            'client' => $data['client'] ?? 'applet',
            'speed' => $data['speed'] ?? '',
        ];
        $this->insert($locationData);
    }

    /**
     * @param $userId
     * @throws ConnectFail
     * @throws PrepareQueryFail
     * @throws \Throwable
     */
    public function userRelation($userId)
    {
        return $this->db->where('user_id', $userId)->get($this->tableName, null, ['friend_id', 'group_id', 'config_id', 'created_at']);
    }

    public function with()
    {
    }


}
