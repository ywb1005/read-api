<?php

namespace App\Models;

use App\Models\Base;

class Download extends Base
{
    //
    protected $casts =[
    ];
    protected $table = 'download';

    const DEFAULT_ITEM = 10;
    /**
     * 获取下载记录
     * @return mixed
     */
    public static function getDownloadList(){
        return self::orderBy('createTime','desc')->paginate(self::DEFAULT_ITEM)->toArray();
    }
}
