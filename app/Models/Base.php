<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    //
     const NO_DELETE = 0;
     const IS_DELETE = 1;
     const DEFAULT_NUM = 10;

    public $timestamps = false;
}
