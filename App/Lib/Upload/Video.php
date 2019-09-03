<?php
namespace App\Lib\Upload;

use App\Lib\Upload\Base;
class Video extends Base{

	/**
	 * fileType
	 * @var string
	 */
	public $fileType = "video";

	public $maxSize = 122;

	/**
	 * 文件后缀的medaiTypw
	 * @var [type]
	 */
	public $fileExtTypes = [
		'mp4',
		'x-flv',
		// todo
	];
}