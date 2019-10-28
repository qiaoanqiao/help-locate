<?php
namespace App\Models\Mysql;

use App\Models\Mysql\BaseMysqlModel;

/** @ODM\Document */
class UserLocation extends BaseMysqlModel
{
    public $tableName = "user_locations";

    /**
     * @param $data
     * @return bool|int
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function createSelf($data)
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

        $model = new self($locationData);
        return $model->save();
    }
}
