<?php
/**
 * Created by PhpStorm.
 * User: huangang
 * Date: 16/3/23
 * Time: 下午4:46
 */

namespace App\Providers;
use App\Models\User;
use Tymon\JWTAuth\Providers\Auth\IlluminateAuthAdapter;

class AuthAdapter extends IlluminateAuthAdapter
{

    private $user;

    /**
     * @param mixed $id
     * @return bool
     */
    public function byId($id)
    {
        $this->user = null;
        $user = User::where('id', $id)->first();
        if(!empty($user)){
            $this->user = $user;
            return true;
        }
        return false;

    }

    /**
     * @return mixed
     */
    public function user(){
        return $this->user;
    }
}