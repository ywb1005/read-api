<?php

namespace App\Models;

use App\Models\Base;

class BookInfo extends Base
{
    //
    protected $table = 'book_info';
    const IS_HOT = 1;
    const IS_NEW = 1;

    /**
     * 添加章节
     * @param $params
     * @return mixed
     */
    public static function createSection($params){
        $section = [
            'nodeName' => $params['name'],
            'nodeId' => $params['index'],
            'bookId' => $params['id'],
            'nodeImg' => $params['image'],
            'url' => $params['url'],
            'delete' => self::NO_DELETE,
            'createTime' => time(),
            'updateTime' => time()
        ];
        return self::insert($section);
    }

    /**
     * 检查章节是否添加
     * @param $bookId
     * @param $nodeId
     * @return mixed
     */
    public static function checkNodeExist($bookId, $nodeId){
        return self::where('bookId', $bookId)->where('nodeId', $nodeId)->where('delete', self::NO_DELETE)->first();
    }

    /**
     * 修改章节信息
     * @param $bookId
     * @param $params
     * @param $id
     * @return mixed
     */
    public static function updateSection($bookId, $params, $id){
        $section = [
            'nodeName' => $params['name'],
            'nodeId' => $params['index'],
            'nodeImg' => $params['image'],
            'url' => $params['url'],
            'delete' => self::NO_DELETE,
            'updateTime' => time()
        ];
        return self::where('id', $id)->where('bookId', $bookId)->update($section);
    }
}
