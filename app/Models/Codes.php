<?php

namespace App\Models;

use App\Models\Base;

class Codes extends Base
{
    //
    protected $casts = [
        'id'=>'integer',
        'type'=>'integer',
        'used'=>'integer'
    ];
    protected $table = 'codes';

    protected $fillable = [
        'id', 'type', 'used', 'code', 'mobile'
    ];

    const NO_USED = 0;
    const IS_USED = 1;

    /**
     * 创建验证码
     * @param $code
     * @param $mobile
     * @param $requestId
     * @param $type
     * @return mixed
     */
    public static function createCode($code, $mobile, $requestId, $type){
        $data = [
            'code'          => $code,
            'mobile'        => $mobile,
            'requestId'     => $requestId,
            'used'          => self::NO_USED,
            'type'          => $type,
            'createTime'    => time(),
            'expireTime'    => time()+900, // 过期时间15分钟
            'updateTime'    => time()
        ];
        $id = self::insertGetId($data);
        return $id;
    }

    /**
     * 根据验证码id获取验证码
     * @param $codeId
     * @param $type
     * @return mixed
     */
    public static function getCode($codeId, $type){
        $check = self::where(['id' => $codeId, 'used' => self::NO_USED, 'type' => $type])->first();
        return $check;
    }

    public static function updateStatus($id){
        return self::where('id', $id)->update(['used' => self::IS_USED, 'updateTime' => time()]);
    }
}
