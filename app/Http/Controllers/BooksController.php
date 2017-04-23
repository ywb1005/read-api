<?php

namespace App\Http\Controllers;

use App\Models\BookInfo;
use App\Models\Books;

class BooksController extends Controller
{
    /**
     * 首页接口
     * @return string
     */
    public function index(){
        $hot = Books::getHotList();
        $new = Books::getNewList();
        $date = [
            'hot' => $hot,
            'new' => $new
        ];
        return $this->output($date);
    }

    public function getMoreList(){
        $type = $this->request('type');
    }

    /**
     * 获取书籍目录接口
     * @param $bookId
     * @return string
     */
    public function getAllSection($bookId){
        $data = BookInfo::where(['bookId' => $bookId, 'delete' => BookInfo::NO_DELETE])
            ->select('id', 'nodeId', 'nodeImg', 'nodeName')
            ->get()->toArray();
        return $this->output($data);
    }

    /**
     * 获取书籍信息接口
     * @param $bookId
     * @return string
     */
    public function getBookInfo($bookId){
        $data = Books::where(['id' => $bookId])
            ->select('id', 'name', 'category', 'abstract', 'keyword', 'author', 'isMember', 'downUrl', 'image')
            ->first()->toArray();
        $data['keyword'] = explode(",", $data['keyword']);
        return $this->output($data);
    }

    /**
     * 书籍查询接口
     * @return string
     */
    public function search(){
        $key = $this->request->get('key');
        if (empty($key)) {
            return $this->error_output('请输入书名');
        }
        $data = Books::search($key);
        if (empty($data)) {
            return $this->error_output('未找到此书');
        }
        return $this->output($data);
    }

    /**
     * 获取书籍章节接口
     * @param $bookId
     * @param $id
     * @return string
     */
    public function getSection($bookId, $id){
        $data = BookInfo::where('nodeId', $id)->where('bookId', $bookId)->where('delete', BookInfo::NO_DELETE)->first();

        $book = Books::where('id', $bookId)->select('name', 'category', 'author')->first();
        $fp = fopen($data['url'], 'r');
        while(!feof($fp)){
            $p = str_replace("\n", "",fgets($fp));
            $node[] =  $p;
        }
        unset($data['createTime'], $data['updateTime'], $data['delete'], $data['bookId']);
        $data['name'] = $book['name'];
        $data['category'] = $book['category'];
        $data['author'] = $book['author'];
        $data['content'] = $node;
        return $this->output($data);
    }

    //admin
    /**
     * 添加书籍接口
     * @return string
     */
    public function createBook(){
        $params = $this->request->all();
        $user = $this->request->user;
        Books::createBook($params, $user->id);
        return $this->output();
    }

    /**
     * 添加章节接口
     * @return string
     */
    public function createSection(){
        $params = $this->request->all();
        $index = BookInfo::checkNodeExist($params['id'], $params['index']);
        if($index){
            return $this->error_output('章节已经存在');
        }
        BookInfo::createSection($params);
        return $this->output();
    }

    /**
     * 获取书籍列表
     * @return string
     */
    public function getBookList(){
        $num = $this->request->get('num');
        $data = Books::bookList($num);
        return $this->output($data);
    }

    /**
     * 根据书籍id获取书籍信息
     * @param $id
     * @return string
     */
    public function getBookInfoById($id){
        $book = Books::where('id', $id)->where('delete', Books::NO_DELETE)
            ->select('id', 'name', 'category', 'abstract', 'keyword', 'author', 'isMember', 'downUrl', 'weight',
                'image', 'isHot', 'isNew')
            ->first();
        $book['downloadUrl'] = $book['downUrl'];
        $book['desc'] = $book['abstract'];
        unset($book['downUrl'], $book['abstract']);
        return $this->output($book);
    }

    /**
     * 修改书籍信息
     * @param $id
     * @return string
     */
    public function updateBook($id){
        $params = $this->request->all();
        $user = $this->request->user;
        Books::saveBook($id, $params, $user->id);
        return $this->output();
    }

    /**
     * 章节列表
     * @param $id
     * @return string
     */
    public function getSectionListById($id){
        $num = $this->request->get('num');
        if(empty($num)){
            $num = 10;
        }
        $data = BookInfo::where('bookId', $id)->where('delete', BookInfo::NO_DELETE)
            ->select('id', 'nodeName', 'nodeImg', 'nodeId')->paginate($num)->toArray();
        return $this->output($data);
    }

    /**
     * 修改章节
     * @param $bookId
     * @param $id
     * @return string
     */
    public function updateSection($bookId, $id){
        $params = $this->request->all();
        BookInfo::updateSection($bookId, $params, $id);
        return $this->output();
    }

    /**
     * 根据章节id获取章节
     * @param $id
     * @return string]
     */
    public function getSectionById($id){
        $section = BookInfo::where('id', $id)->where('delete', Books::NO_DELETE)
            ->select('id', 'nodeName', 'nodeId', 'nodeImg')
            ->first();
        return $this->output($section);
    }
}
