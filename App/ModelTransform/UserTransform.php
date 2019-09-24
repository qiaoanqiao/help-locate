<?php


namespace App\ModelTransform;


class UserTransform
{
    /**
     * 用户个人中心信息
     * @param array $data
     * @return array
     */
    public function personalCenter(array $data)
    {
        return [
            'name' => $data['name'] ?: '',
            'note_name' => $data['note_name'] ?: '',
            'mobile' => empty($data['mobile']) ? '' : substr_replace($data['mobile'], '****', 3, 4),
            'is_vip' => (bool)$data['is_vip'],
        ];
    }

}