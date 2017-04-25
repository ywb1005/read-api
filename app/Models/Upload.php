<?php

namespace App\Models;

use App\Models\Base;

class Upload extends Base
{
    //
    protected $casts =[
    ];
    protected $table = 'upload';
    const DEFAULT_ITEM = 10;

    /**
     * 查询会员上传列表
     * @return mixed
     */
    public static function getUploadList(){
        return self::orderBy('createTime','desc')->paginate(self::DEFAULT_ITEM)->toArray();
    }
}
