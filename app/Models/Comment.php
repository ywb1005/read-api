<?php

namespace App\Models;

use App\Models\Base;

class Comment extends Base
{
    //
    protected $table = 'comments';

    const COMMENT_DEFAULT_ITEM = 5;

    /**
     * 获取书籍评论列表
     * @param $bookId
     * @return mixed
     */
    public static function getCommentList($bookId){
        return self::where(['delete' => self::NO_DELETE, 'bookId' => $bookId])
            ->orderBy('createTime','desc')->paginate(self::COMMENT_DEFAULT_ITEM)->toArray();
    }

    /**
     * 新增评论
     * @param $user
     * @param $bookId
     * @param $content
     * @return mixed
     */
    public static function createComment($user, $bookId, $content){
        $comment = [
            'userId' => $user,
            'bookId' => $bookId,
            'comment'=> $content,
            'delete' => self::NO_DELETE,
            'createTime' => time()
        ];
        return self::insertGetId($comment);
    }

    /**
     * 获取全部评论
     * @return mixed
     */
    public static function getAllCommentList(){
        return self::where(['delete' => self::NO_DELETE])
            ->orderBy('createTime','desc')->paginate(self::DEFAULT_NUM)->toArray();
    }

    /**
     * 根据用户名查询评论
     * @param $key
     * @return mixed
     */
    public static function search($key){
        $user = User::where('username', 'like', '%'.$key.'%')->first();
        return self::where('userId', $user['id'])
            ->orderBy('createTime','desc')->paginate(self::DEFAULT_NUM)->toArray();
    }
}
