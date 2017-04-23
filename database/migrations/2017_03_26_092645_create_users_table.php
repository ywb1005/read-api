<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create user_table
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->string('username')->comment('用户名')->nullable();;
            $table->string('mobile', 11)->comment('手机号')->unique();
            $table->string('email', 20)->comment('电子邮件')->nullable();
            $table->string('password', 64)->comment('密码');
            $table->integer('roleId')->comment('角色类型(1-普通用户, 2-管理员)')->unsigned()->default(1);
            $table->integer('createTime')->comment('创建时间')->unsigned(true)->default(1);
            $table->integer('updateTime')->comment('更新时间')->unsigned(true)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop table
        Schema::drop('users');
    }
}
