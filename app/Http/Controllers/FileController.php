<?php

namespace App\Http\Controllers;


use App\Libs\Upload;

class FileController extends Controller
{
    //

    /**
     * 上传图片接口
     * @return string
     */
    public function file(){
        $file = $this->request->file('image');
        $upload = new Upload();
        $message = $upload->uploadFile(1, $file);
        return $this->output($message['info']['url']);
    }

    public function files(){
        $file = $this->request->file('files');
        $upload = new Upload();
        $message = $upload->uploadFile(2, $file);
        return $this->output($message['info']['url']);
    }
}
