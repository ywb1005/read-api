<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->string('mobile',11)->comment('手机号');
            $table->integer('code')->comment('验证码');
            $table->text('requestId')->comment('阿里云请求id');
            $table->integer('used')->comment('是否使用过 1-是, 0-否');
            $table->integer('type')->comment('短信类型 1-验证码, 2-随机密码');
            $table->integer('createTime')->comment('创建时间')->unsigned(true)->default(1);
            $table->integer('expireTime')->comment('过期时间')->unsigned(true)->default(1);
            $table->integer('updateTime')->comment('使用时间')->unsigned(true)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dorp('codes');
    }

}
