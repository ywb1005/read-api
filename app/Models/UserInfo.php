<?php

namespace App\Models;

use App\Models\Base;

class UserInfo extends Base
{
    //
    protected $casts =[
        'roleId' => 'int',
        'collectBook' => 'string'
    ];
    protected $table = 'user_info';


}
