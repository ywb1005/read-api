<?php

namespace App\Http\Controllers;

use App\Models\User;
use JWTAuth;

class UserController extends Controller
{
    //
    public function register(){
        $param = $this->request->all();
        $user= User::where('id', '1')->first();
        $token = JWTAuth::setToken($user->username);
        return $this->output($user,$token);
    }
    public function testToken(){
        $mobile = $this->request->get('mobile');
        $user = User::where('mobile', $mobile)->first();
        $token = JWTAuth::fromUser($user);
        return $this->output($token);
    }

    public function checkToken(){
        return $this->output('token is valid');
    }
}
