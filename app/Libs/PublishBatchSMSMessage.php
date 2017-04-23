<?php
/**
 * Created by PhpStorm.
 * User: xz
 * Date: 2017/04/01
 * Time: 20:57
 */
namespace App\Libs;
require_once app_path().('/Libs/AliSms/mns-autoloader.php');
use AliyunMNS\Client;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;
class PublishBatchSMSMessage
{

    //访问端
    protected $endPoint;

    //阿里云访问id
    protected $accessId;

    //阿里云访问key
    protected $accessKey;

    //初始化客户端对象
    protected $client;

    //主题名称与访问端挂钩
    protected $topicName;

    //短信签名
    protected $messageName;

    /**
     * 初始化参数
     * PublishBatchSMSMessage constructor.
     */
    public function __construct()
    {
        $this->endPoint = env('ALIYUN_SMS_ENDPOINT', ''); // eg. http://1234567890123456.mns.cn-shenzhen.aliyuncs.com
        $this->accessId = env('ALIYUN_ACCESS_ID', '');
        $this->accessKey = env('ALIYUN_ACCESS_KEY', '');
        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        $this->topicName = env('ALIYUN_SMS_TOPICNAME', '');
        $this->messageName = env('ALIYUN_SMS_MESSAGENAME', '');
    }

    /**
     * 获取主题
     * @return \AliyunMNS\Topic
     */
    public function getTopic(){
        $topic = $this->client->getTopicRef($this->topicName);
        return $topic;
    }

    /**
     * 设置消息主体
     * 还没研究过
     * @param $messageBody
     * @return string
     */
    public function getMessageBody($messageBody = "smsmessage"){
        return $messageBody;
    }

    /**
     * 封装消息参数
     * @return MessageAttributes
     */
    public function setMessageAttributes($templateCode, $mobile, $content = []){
        $batchSmsAttributes = new BatchSmsAttributes($this->messageName, $templateCode);
        $batchSmsAttributes->addReceiver($mobile, $content);
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        return $messageAttributes;
    }


    public function run($templateCode, $mobile, $content = [])
    {
        $messageBody = $this->getMessageBody();
        $messageAttributes = $this->setMessageAttributes($templateCode, $mobile, $content);
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try
        {
            $res = $this->getTopic()->publishMessage($request);
            $result = [
                'code' => $res->isSucceed(),
                'messageId' => $res->getMessageId()
            ];
            return $result;
        }
        catch (MnsException $e)
        {
            return $e->getMnsErrorCode;
        }
    }
}