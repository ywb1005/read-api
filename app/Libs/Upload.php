<?php
/**
 * Created by PhpStorm.
 * User: xz
 * Date: 2017/04/05
 * Time: 14:19
 */

namespace App\Libs;


use OSS\OssClient;

class Upload
{
    //阿里云访问id
    protected $accessId;

    //阿里云访问key
    protected $accessKey;

    //初始化客户端对象
    protected $client;

    //访问域名
    protected $endPoint;

    //存储bucket名
    protected $bucket;


    /**
     * init
     * Upload constructor.
     */
    public function __construct()
    {
        $this->accessId = env('ALIYUN_ACCESS_ID', '');
        $this->accessKey = env('ALIYUN_ACCESS_KEY', '');
        $this->endPoint = env('ALIYUN_OSS_ENDPOINT', '');
        $this->bucket = env('ALIYUN_OSS_IMAGE_BUCKET', '');
        $this->client = new OssClient($this->accessId, $this->accessKey, $this->endPoint);
    }

    /**
     * 上传文件
     * @param $ossClient
     * @param $type
     * @param $object
     */
    public function uploadFile($type, $filePath)
    {
        $root = $this->getFilePath($type);
        $fileName = "upload/". $root . date('Ymd') .'/'. md5(time()) . rand(1, 999) . "." . $filePath->getClientOriginalExtension();
        try{
            $res = $this->client->uploadFile($this->bucket, $fileName, $filePath);
        } catch(OssException $e) {
            throw new \Exception($e->getMessage());
        }
        return $res;
    }

    /**
     * 根据type获取上传的是文件目录
     * @param $type 1-表示图片存储bucket,2-表示书籍压缩包存储bucket
     * @return string
     */
    public function getFilePath($type)
    {
        switch ($type){
            case 1:
                $root = 'image/';
                break;
            case 2:
                $root = 'books/';
                break;
        }
        return $root;
    }
}