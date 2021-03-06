<?php

namespace App\Http\Controllers;


use App\Models\Upload;
use App\Models\Books;
use App\Models\Download;
use App\Models\User;

class OptionController extends Controller
{
    //


    /**
     * 获取下载记录
     * @return string
     */
    public function getDownloadList(){
        $data = Download::getDownloadList();
        foreach ($data['data'] as $value){
            $user = User::where('id', $value['userId'])->first();
            $book = Books::getBookById($value['bookId']);
            $info['id'] = $value['id'];
            $info['username'] = $user['username'];
            $info['bookName'] = $book['name'];
            $info['category'] = $book['category'];
            $info['createTime'] = date('Y-m-d H:i:s', $value['createTime']);
            $list[] = $info;
        }
        unset($data['data']);
        $data['list'] = $list;
        return $this->output($data);
    }

    /**
     * 获取会员上传记录
     * @return string
     */
    public function getUploadList(){
        $data = Upload::getUploadList();
        foreach ($data['data'] as $value){
            $user = User::where('id', $value['userId'])->first();
            $info['id'] = $value['id'];
            $info['username'] = $user['username'];
            $info['bookName'] = $value['bookName'];
            $info['bookAuthor'] = $value['bookAuthor'];
            $info['url'] = $value['bookUrl'];
            $info['createTime'] = date('Y-m-d H:i:s', $value['createTime']);
            $list[] = $info;
        }
        unset($data['data']);
        $data['list'] = $list;
        return $this->output($data);
    }
}
