<?php
namespace App\Lib\Cache;
use App\Model\Video as VideoModel;
//use EasySwoole\Core\Component\Cache\Cache ;
use EasySwoole\FastCache\Cache;
#use EasySwoole\Core\Component\Di;
use EasySwoole\Component\Di;
class Video {
	public function setIndexVideo() {
		$catIds = array_keys(\Yaconf::get("category.cats"));
		array_unshift($catIds, 0);
		$cacheType = \Yaconf::get("base.indexCacheType");

		$modelObj = new VideoModel();
		// 写 video json 缓存数据
		foreach($catIds as $catId) {
			$condition = [];
			if(!empty($catId)) {
				$condition['cat_id'] = $catId;	
			}
			try {
				$data = $modelObj->getVideoCacheData($condition);
			}catch(\Exception $e) {
				// 报警 短信 邮件
				$data = [];
			}

			if(empty($data)) {
				continue;
			}

			foreach($data as &$list) {
                $list['create_time'] = date("Ymd H:i:s", $list['create_time']);
                // 00:01:07  
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }

            switch ($cacheType) {
            	case 'file':
            		$res = file_put_contents($this->getVideoCatIdFile($catId), json_encode($data));
            		break;
            	case 'table':
					//$res = Cache::getInstance()->set($this->getCatKey($catId), $data);
					$res = Cache::getInstance()->set($this->getCatKey($catId), $data);
            		break;
            	case 'redis':
            	 	$res = Di::getInstance()->get("REDIS")->set($this->getCatKey($catId), $data);
            	 	break;
            	default:
            		throw new \Exception("请求不合法");
            		break;
            }

            if(empty($res)) {
            	// 记录日志  报警 
            }
            // file
            //$flag = file_put_contents(EASYSWOOLE_ROOT."/webroot/video/json/".$catId.".json", json_encode($data));
     
     		// easyswoole cache
            //Cache::getInstance()->set("index_video_data_cat_id_".$catId, $data);
            //
            // redis
            //Di::getInstance()->get("REDIS")->set("index_video_data_cat_id_".$catId, $data);
            
		}

	}

	public function getCache($catId = 0) {
		$cacheType = \Yaconf::get("base.indexCacheType");
		switch ($cacheType) {
			case 'file':
				$videoFile = $this->getVideoCatIdFile($catId);
				$videoData = is_file($videoFile) ? file_get_contents($videoFile) : [];
				$videoData = !empty($videoData) ? json_decode($videoData, true) : [];
				break;

			case 'table':
				$videoData = Cache::getInstance()->get($this->getCatKey($catId));
				$videoData = !empty($videoData) ? $videoData : [];
				break;
			case 'redis':
			 	$videoData = Di::getInstance()->get("REDIS")->get($this->getCatKey($catId));
			 	$videoData = !empty($videoData) ? json_decode($videoData, true) : [];
			 	break;
			default:
				throw new \Exception("请求不合法");
				break;
		}

		return $videoData;
	}
	/**
	 * [getVideoCatIdFile description]
	 * @auth   singwa
	 * @param  integer $catId [description]
	 * @return [type]         [description]
	 */
	public function getVideoCatIdFile($catId = 0) {
		return EASYSWOOLE_ROOT."/webroot/video/json/".$catId.".json";
	}
	/**
	 * [getCatKey description]
	 * @auth   singwa
	 * @param  integer $catId [description]
	 * @return [type]         [description]
	 */
	public function getCatKey($catId = 0) {
		return "index_video_data_cat_id_".$catId;
	}
}