<?php

namespace App\Models;

use App\Models\Base;

class AdminUser extends Base
{
    //
    protected $casts =[
        'roleId' => 'int'
    ];
    protected $table = 'admin_user';

    const ROLE_TYPE_COMMON = 1; //普通管理员

    /**
     * 注册新用户
     * @param $mobile
     * @param $password
     * @return mixed
     */
    public static function createUser($username, $password){
        $user = [
            'username'      => $username,
            'password'      => md5($password),
            'roleId'        => self::ROLE_TYPE_COMMON,
            'delete'        => self::NO_DELETE,
            'createTime'    => time(),
            'updateTime'    => time()
        ];
        self::insert($user);
        return $user;
    }
}
