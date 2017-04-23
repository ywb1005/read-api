<?php
/**
 * Created by PhpStorm.
 * User: xz
 * Date: 2017/04/01
 * Time: 21:58
 */

namespace App\Libs;
use App\Libs\PublishBatchSMSMessage;
use App\Models\Codes;

class Sms
{
    const TEMPLATE_TYPE_CHECK = 1;
    const TEMPLATE_TYPE_PWD = 2;

    public static function getCode(){
        $code = rand(100000,999999);
        return $code;
    }

    /**
     * 发送短信
     * @param $templateCode 短信模版
     * @param $mobile 手机号
     * @param int $type 1-验证码, 2-随机密码
     */
    public static function sendMessage($mobile, $type = self::TEMPLATE_TYPE_CHECK){
        $sendClient = new PublishBatchSMSMessage();
        $code = self::getCode();
        switch ($type) {
            case self::TEMPLATE_TYPE_CHECK:
                $templateCode = env('ALIYUN_SMS_CODE_TEMPLATE', '');
                $content = [
                    'code' => "$code"
                ];
                break;
            case self::TEMPLATE_TYPE_PWD:
                $templateCode = env('ALIYUN_SMS_PWD_TEMPLATE', '');
                $content = [
                    'password' => "$code"
                ];
                break;
        }
        $res = $sendClient->run($templateCode, $mobile, $content);
        if($res['code']){
            if($type == 1){
                $id = Codes::createCode($code, $mobile, $res['messageId'], $type);
                return $id;
            }else {
                return $code;
            }
        }
    }
}