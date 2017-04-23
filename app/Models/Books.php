<?php

namespace App\Models;

use App\Models\Base;

class Books extends Base
{
    //
    protected $table = 'books';
    const IS_HOT = 1;
    const IS_NEW = 1;

    protected $casts = [
        'isNew'     => 'bool',
        'isMember'  => 'bool',
        'isHot'     => 'bool',
    ];

    /**
     * 获取首页热门书籍
     * @return mixed
     */
    public static function getHotList(){
        return self::where('isHot', self::IS_HOT)->select('id', 'name', 'author', 'isHot', 'image')->take(12)->get();
    }

    /**
     * 获取首页新书推荐
     * @return mixed
     */
    public static function getNewList(){
        return self::where('isNew', self::IS_NEW)->select('id', 'name', 'author', 'isHot', 'image')->take(6)->get();
    }

    /**
     * 根据书名搜索
     * @param $key
     * @return mixed
     */
    public static function search($key){
        return self::where('name','like', '%'.$key.'%')->first();
    }

    /**
     * 根据书籍id获取书籍信息
     * @param $bookId
     * @return mixed
     */
    public static function getBookById($bookId){
        return self::where('id',$bookId)->first();
    }

    /**
     * 添加新书
     * @param $params 书籍信息数组
     * @param $user 用户id
     * @return mixed
     */
    public static function createBook($params, $user){
        $params['downUrl'] = $params['downloadUrl'];
        $params['abstract'] = $params['desc'];
        unset($params['downloadUrl'], $params['desc']);
        $params['delete'] = self::NO_DELETE;
        $params['createUser'] = $params['updateUser'] = $user;
        $params['createTime'] = $params['updateTime'] = time();
        return self::insert($params);
    }

    /**
     * 获取书籍列表
     * @param $num
     * @return mixed
     */
    public static function bookList($num){
        if (empty($num)){
            $num =  self::DEFAULT_NUM;
        }
        return self::where('delete', self::NO_DELETE)->select('id', 'name', 'category', 'author', 'image')->paginate($num)->toArray();
    }

    /**
     * 更新书籍
     * @param $id
     * @param $params
     * @param $user
     * @return mixed
     */
    public static function saveBook($id, $params, $user){
        $params['downUrl'] = $params['downloadUrl'];
        $params['abstract'] = $params['desc'];
        unset($params['downloadUrl'], $params['desc']);
        $params['updateUser'] = $user;
        $params['updateTime'] = time();
        return self::where('id', $id)->update($params);
    }
}
