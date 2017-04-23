<?php

namespace App\Http\Controllers;
use App\Models\Books;
use App\Models\Comment;
use App\Models\User;

class CommentController extends Controller
{
    //
    /**
     * 评论列表接口
     * @param $bookId
     * @return string
     */
    public function commentList($bookId){
        $list = Comment::getCommentList($bookId);

        $info = [];

        foreach ($list['data'] as $one) {
            $user = User::where('id', $one['userId'])->first();
            if (!empty($user)){
                $one['username'] = $user['username'];
            }else {
                $one['username'] = '未知';
            }
            unset($one['userId'], $one['bookId'], $one['delete']);
            $one['createTime'] = date('Y-m-d H:i:s', $one['createTime']);
            $info[] = $one;
        }

        unset($list['data']);
        $list['list'] = $info;

        return $this->output($list);
    }

    /**
     * 新增评论接口
     * @param $bookId
     * @return string
     */
    public function setComment($bookId){
        $user = $this->request->user;
        $comment = $this->request->get('content');
        $id = Comment::createComment($user->id, $bookId, $comment);
        return $this->output($id);
    }


    //后台接口
    /**
     * 后台获取所有评论
     * @return string
     */
    public function getAdminCommentList(){
        $list = Comment::getAllCommentList();

        $info = [];

        foreach ($list['data'] as $one) {
            $user = User::where('id', $one['userId'])->first();
            if (!empty($user)){
                $one['username'] = $user['username'];
            }else {
                $one['username'] = '未知';
            }
            if($user['roleId'] == 1){
                $one['role'] = '普通会员';
            }else{
                $one['role'] = '未知';
            }

            $book = Books::getBookById($one['bookId']);
            $one['bookName'] = $book['name'];
            unset($one['userId'], $one['bookId'], $one['delete']);
            $one['createTime'] = date('Y-m-d H:i:s', $one['createTime']);
            $info[] = $one;
        }

        unset($list['data']);
        $list['list'] = $info;

        return $this->output($list);
    }

    /**
     * 删除评论接口
     * @param $id
     * @return string
     */
    public function deleteComment($id){
        Comment::where('id', $id)->update(['delete' => Comment::IS_DELETE]);
        return $this->output();
    }

    public function search(){
        $key = $this->request->get('key');
        $data = Comment::search($key);
        if (!empty($data['data'])){
            foreach ($data['data'] as $one) {
                $user = User::where('id', $one['userId'])->first();
                if (!empty($user)){
                    $one['username'] = $user['username'];
                }else {
                    $one['username'] = '未知';
                }
                if($user['roleId'] == 1){
                    $one['role'] = '普通会员';
                }else{
                    $one['role'] = '未知';
                }

                $book = Books::getBookById($one['bookId']);
                $one['bookName'] = $book['name'];
                unset($one['userId'], $one['bookId'], $one['delete']);
                $one['createTime'] = date('Y-m-d H:i:s', $one['createTime']);
                $info[] = $one;
            }

            unset($data['data']);
            $data['list'] = $info;
        }
        return $this->output($data);
    }
}
