<?php
namespace App\Lib\AliyunSdk;
require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-php-sdk-core/Config.php';   // 假定您的源码文件和aliyun-php-sdk处于同一目录。
require_once EASYSWOOLE_ROOT.'/App/Lib/AliyunSdk/aliyun-oss-php-sdk-master/autoload.php';

use vod\Request\V20170321 as vod;
use OSS\OssClient;
use OSS\Core\OssException;

class AliVod {


	public $regionId = "cn-shanghai";
	public $client ;
	public $ossClient;

	public function __construct() {
	    $profile = \DefaultProfile::getProfile($this->regionId, \Yaconf::get("aliyun.accessKeyId"), \Yaconf::get("aliyun.accessKeySecret"));
	    $this->client = new \DefaultAcsClient($profile);
	}

	/**
	 * [create_upload_video description]
	 * @auth   singwa
	 * @date   2018-10-29T07:58:49+0800
	 * @param  [type]                   $vodClient [description]
	 * @return [type]                              [description]
	 */
	public function createUploadVideo($title, $videoFileName, $other = []) {
	    $request = new vod\CreateUploadVideoRequest();
	    $request->setTitle($title);        // 视频标题(必填参数)
	    $request->setFileName($videoFileName); // 视频源文件名称，必须包含扩展名(必填参数)
	    if(!empty($other['description'])) {
	    	$request->setDescription("视频描述");  // 视频源文件描述(可选)
		}
		// tags 
	    
	    $result = $this->client->getAcsResponse($request);
	    if(empty($result) || empty($result->VideoId)) {
	    	throw new \Exception("获取上传凭证不合法");
	    }
	    return $result;
	}

	public function initOssClient($uploadAuth, $uploadAddress) {
		//$uploadAddress['Endpoint'] = "http://oss-cn-shanghai.aliyuncs.com";
    $this->ossClient = new OssClient($uploadAuth['AccessKeyId'], $uploadAuth['AccessKeySecret'], $uploadAddress['Endpoint'], 
        false, $uploadAuth['SecurityToken']);
    	$this->ossClient->setTimeout(86400*7);    // 设置请求超时时间，单位秒，默认是5184000秒, 建议不要设置太小，如果上传文件很大，消耗的时间会比较长
    	$this->ossClient->setConnectTimeout(10);  // 设置连接超时时间，单位秒，默认是10秒
    
	}

	function uploadLocalFile($uploadAddress, $localFile) {
    	return $this->ossClient->uploadFile($uploadAddress['Bucket'], $uploadAddress['FileName'], $localFile);
	}

	/**
	 * [getPlayInfo description]
	 * @auth   singwa
	 * @date   2018-10-29T09:19:05+0800
	 * @param  integer                  $videoId [description]
	 * @return [type]                            [description]
	 */
	public function getPlayInfo($videoId = 0) {

		if(empty($videoId)) {
			return [];
		}
		$request = new vod\GetPlayInfoRequest();
		$request->setVideoId($videoId);
		$request->setAcceptFormat("JSON");

		return $this->client->getAcsResponse($request);
	}
}