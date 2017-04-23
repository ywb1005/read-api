<?php

namespace App\Models;

use App\Models\Base;

class User extends Base
{
    //
    protected $casts =[
        'roleId' => 'int'
    ];
    protected $table = 'users';

    const ROLE_TYPE_COMMON = 1;//会员
    const ROLE_TYPE_ADMIN = 1;//管理员
    const DEFAULT_SEARCH_TYPE = 1; //默认id搜索

    /**
     * 注册新用户
     * @param $mobile
     * @param $password
     * @return mixed
     */
    public static function createUser($mobile, $password, $email){
        $user = [
            'username'      => $mobile,
            'mobile'        => $mobile,
            'password'      => md5($password),
            'roleId'        => self::ROLE_TYPE_COMMON,
            'createTime'    => time(),
            'updateTime'    => time()
        ];
        if ($email != null){
            $user['email'] = $email;
        }
        self::insert($user);
        return $user;
    }

    /**
     * 获取会员列表
     * @return mixed
     */
    public static function getUserList($num){
        if (empty($num)){
            $num =  self::DEFAULT_NUM;
        }
        return self::where('roleId', self::ROLE_TYPE_COMMON)->paginate($num)->toArray();
    }

    /**
     * 条件查询用户
     * @param int $type
     * @param $key
     * @return mixed
     */
    public static function search($type = self::DEFAULT_SEARCH_TYPE, $key){
        switch ($type) {
            case 1:
                $query = self::where('id', $key)->first();
                break;
            case 2:
                $query = self::where('username', 'like', '%'.$key.'%')->first();
                break;
            case 3:
                $query = self::where('mobile', $key)->first();
                break;
        }
        return $query;
    }
}
