<?php

namespace App\Http\Controllers;
use App\Models\Books;
use App\Models\Codes;
use App\Models\Download;
use App\Models\Upload;
use App\Models\User;
use App\Models\AdminUser;
use JWTAuth;
use App\Models\UserInfo;


class UserController extends Controller
{
    /**
     * 用户注册接口
     * @return string
     */
    public function register(){
        $this->validate($this->request, [
            'mobile'    => 'required',
            'code'      => 'required',
            'password'  => 'required'
        ]);
        $param = $this->request->all();

        //校验手机号
        $mobile = preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/",$param['mobile']);
        if(!$mobile){
            return $this->error_output('手机号错误');
        }
        //验证码校验
        $code = $param['code'];
        $codeId = (int)$param['codeId'];

        $codeData = Codes::getCode($codeId, 1);
        if(empty($codeData)){
            return $this->error_output('无效验证码');
        }else{
            if($codeData['code'] !== (int)$code){
                return $this->error_output('错误验证码');
            }
            if($codeData['expireTime'] < time()){
                return $this->error_output('验证码已过期');
            }
        }

        Codes::updateStatus($codeId);

        //创建用户
        User::createUser($param['mobile'], $param['password'], null);

        return $this->output();
    }

    /**
     * 发送短信接口
     * @return string
     */
    public function getSms(){
        $mobile = $this->request->get('mobile');
        $type   = $this->request->get('type');
        $codeId = \App\Libs\Sms::sendMessage($mobile, $type);
        return $this->output($codeId);
    }

    /**
     * 重置密码
     * @return string
     */
    public function resetPwd(){
        $mobile = $this->request->get('mobile');
        $type   = $this->request->get('type');
        $code = \App\Libs\Sms::sendMessage($mobile, $type);
        User::where('mobile', $mobile)->update(
            [
                'password' => md5($code),
                'updateTime' => time()
            ]
        );
        return $this->output();
    }

    /**
     * 用户登录接口
     * @return string
     */
    public function login(){
        $this->validate($this->request, [
            'mobile'    => 'required',
            'password'  => 'required'
        ]);
        $mobile     = $this->request->get('mobile');
        $password   = $this->request->get('password');

        $user = User::where('mobile', $mobile)->first();
        if(!empty($user)){
            if($user['password'] === md5($password)){
                $token = JWTAuth::fromUser($user);
            }else{
                return $this->error_output('密码错误!');
            }
        }else{
            return $this->error_output('用户未注册!');
        }
        return $this->output(['name' => $user['username'], 'mobile' => $mobile,'token' => $token]);
    }

    /**
     * 创建会员信息接口
     * @return string
     */
    public function createUserInfo(){
        $user   = $this->request->user;
        $email  = $this->request->get('email');
        $username  = $this->request->get('username');
        $age = $this->request->get('age');
        $hobby = $this->request->get('hobby');
        $gender = $this->request->get('gender');
        User::where('id', $user->id)->update(['username' => $username, 'email' => $email, 'updateTime' => time()]);
        $info = UserInfo::where('userId', $user->id)->first();
        if (!empty($info)){
            UserInfo::where('id', $info['id'])
                ->update(['age' => $age, 'hobby' => $hobby, 'gender' => $gender, 'updateTime' => time()]);
        }else {
            UserInfo::insert([
                'age' => $age,
                'hobby' => $hobby,
                'gender' => $gender,
                'createTime' => time(),
                'updateTime' => time()
            ]);
        }
        return $this->output();
    }

    /**
     * 获取会员详细信息
     * @return string
     */
    public function getUserInfo(){
        $user = $this->request->user;
        $data = User::where('id', $user->id)->select('id', 'username', 'email', 'mobile')->first();
        $info = UserInfo::where('userId', $user->id)->select('age', 'gender', 'hobby', 'collectBook')->first();
        $data['age'] = $info['age'];
        $data['gender'] = $info['gender'];
        $data['hobby'] = $info['hobby'];
        $arr = explode(',', $info['collectBook']);
        foreach ($arr as $value){
            $bookInfo = Books::getBookById($value);
            $img[] = $bookInfo['image'];
        }
        $data['collectBook'] = $img;
        return $this->output($data);
    }

    /**
     * 添加收藏书籍
     * @return string
     */
    public function addCollect(){
        $user = $this->request->user;
        $book = $this->request->get('bookId');
        $info = UserInfo::where('userId', $user->id)->first();
        if (!empty($info)){
            if(empty($info['collectBook'])){
                $bookId = $book;
            }else{
                $bookId = $info['collectBook'].','.$book;
            }
            UserInfo::where('id', $info['id'])->update(['collectBook' => $bookId, 'updateTime' => time()]);
        }else {
            UserInfo::insert(['collectBook' => $book, 'createTime' => time(),
                'updateTime' => time()]);
        }
        return $this->output();
    }

    /**
     * 判断用户是否已经收藏
     * @return string
     */
    public function isCollect(){
        $user = $this->request->user;
        $book = $this->request->get('bookId');
        $info = UserInfo::where('userId', $user->id)->first();
        if(!empty($info)){
            $arr = explode(',',$info['collectBook']);
            $flag = in_array($book, $arr);
        }
        if($flag){
            return $this->output(1);
        }else{
            return $this->output(0);
        }
    }

    /**
     * 取消收藏书籍
     * @return string
     */
    public function cancelCollect(){
        $user = $this->request->user;
        $book = $this->request->get('bookId');
        $info = UserInfo::where('userId', $user->id)->first();
        if(!empty($info)){
            $arr = explode(',',$info['collectBook']);
            foreach ($arr as $value){
                if ($value != $book){
                    $new[] = $value;
                }
            }
            $str = implode(',', $new);
            UserInfo::where('id', $info['id'])->update(['collectBook' => $str, 'updateTime' => time()]);
        }
        return $this->output();
    }

    /**
     * 会员修改密码接口
     * @return string
     */
    public function updateUserPwd(){
        $this->validate($this->request, [
            'oldPwd'    => 'required',
            'newPwd'    => 'required'
        ]);
        $id = $this->request->user->id;
        $params = $this->request->all();
        $user = User::where('id', $id)->first();
        if(md5($params['oldPwd']) === $user['password']){
            if(md5($params['newPwd']) === $user['password']){
                return $this->error_output('新密码与旧密码相同');
            }else{
                $update = [
                    'updateTime' => time(),
                    'password' => md5($params['newPwd'])
                ];
                User::where('id', $id)->update($update);
            }
        }else{
            return $this->error_output('旧密码错误');
        }
        return $this->output();
    }

    /**
     * 上传书籍接口
     * @return string
     */
    public function uploadBook(){
        $user = $this->request->user;
        $params = $this->request->all();
        $info = [
            'userId' => $user->id,
            'bookName' => $params['name'],
            'bookAuthor'=> $params['author'],
            'bookUrl' => $params['url'],
            'createTime' => time(),
            'updateTime' => time()
        ];
        Upload::insert($info);
        return $this->output();
    }

    /**
     * 记录会员下载记录
     * @return string
     */
    public function downloadLog(){
        $user = $this->request->user;
        $params = $this->request->get('bookId');
        $info = [
            'userId' => $user->id,
            'bookId' => $params,
            'createTime' => time(),
            'updateTime' => time()
        ];
        Download::insert($info);
        return $this->output();
    }

    //admin后台接口部分
    /**
     * 获取会员列表
     * @return string
     */
    public function userList(){
        $num = $this->request->get('num');
        $data = User::getUserList($num);
        $list = [];
        foreach ($data['data'] as $item){
            if($item['roleId'] == 1){
                $item['role'] = '普通会员';
                unset($item['roleId']);
            }
            $item['date'] = date('Y-m-d H:i:s', $item['createTime']);
            unset($item['createTime'], $item['updateTime']);
            if(empty($item['email'])){
                $item['email'] = '未填写';
            }
            $list[] = $item;
        }
        unset($data['data']);
        $data['list'] = $list;
        return $this->output($data);
    }

    /**
     * 查询用户接口
     * @return string
     */
    public function search(){
        $type = $this->request->get('type');
        $key  = $this->request->get('key');
        $user = User::search($type, $key);
        if($user['roleId'] == 1){
            $user['role'] = '普通会员';
            unset($user['roleId']);
        }
        $user['date'] = date('Y-m-d H:i:s', $user['createTime']);
        unset($user['createTime'], $user['updateTime']);
        if(empty($user['email'])){
            $user['email'] = '未填写';
        }
        return $this->output($user);
    }

    /**
     * 后台登录
     * @return string
     */
    public function adminLogin(){
        $this->validate($this->request, [
            'username'    => 'required',
            'password'  => 'required'
        ]);
        $username   = $this->request->get('username');
        $password   = $this->request->get('password');

        $user = AdminUser::where('username', $username)->first();
        if(!empty($user)){
            if($user['password'] === md5($password)){
                $token = JWTAuth::fromUser($user);
            }else{
                return $this->error_output('密码错误!');
            }
        }else{
            return $this->error_output('用户不存在!');
        }
        return $this->output(['name' => $user['username'], 'token' => $token]);
    }

    /**
     * 添加会员
     * @return string
     */
    public function createMember(){
        $this->validate($this->request, [
            'mobile'    => 'required',
            'password'  => 'required'
        ]);
        $param = $this->request->all();

        //校验手机号
        $mobile = preg_match("/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/",$param['mobile']);
        if(!$mobile){
            return $this->error_output('手机号错误');
        }

        //创建用户
        User::createUser($param['mobile'], $param['password'], $param['email']);

        return $this->output();
    }

    /**
     * 添加管理员
     * @return string
     */
    public function addAdminUser(){
        $this->validate($this->request, [
            'username'    => 'required',
            'password'  => 'required'
        ]);

        $params = $this->request->all();

        AdminUser::createUser($params['username'], $params['password']);
        return $this->output();
    }

    /**
     * 获取全部管理员
     * @return string
     */
    public function getAdminUser(){
        $user = AdminUser::where('delete', AdminUser::NO_DELETE)->get();
        foreach ($user as $value){
            $value['date'] = date('Y-m-d H:i:s', $value['createTime']);
            if($value['roleId']){
                $value['role'] = '普通管理员';
            }else{
                $value['role'] = '未设置';
            }
            unset($value['delete'], $value['updateTime'], $value['roleId'], $value['createTime']);
        }
        return $this->output($user);
    }

    /**
     * 删除用户
     * @param $id
     * @return string
     */
    public function deleteAdminUser($id){
        $user = [
            'delete' => AdminUser::IS_DELETE,
            'updateTime' => time()
        ];
        AdminUser::where('id', $id)->update($user);
        return $this->output();
    }

    /**
     * 修改管理员密码
     * @param $id
     * @return string
     */
    public function updateAdminUser($id){
        $this->validate($this->request, [
            'oldPwd'    => 'required',
            'newPwd'    => 'required'
        ]);

        $params = $this->request->all();
        $user = AdminUser::where('id', $id)->first();
        if(md5($params['oldPwd']) === $user['password']){
            if(md5($params['newPwd']) === $user['password']){
                return $this->error_output('新密码与旧密码相同');
            }else{
                $update = [
                  'updateTime' => time(),
                  'password' => md5($params['newPwd'])
                ];
                AdminUser::where('id', $id)->update($update);
            }
        }else{
            return $this->error_output('旧密码错误');
        }
        return $this->output();
    }
}
