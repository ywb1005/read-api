<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('admin_user', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->string('username')->comment('用户名');
            $table->string('password', 64)->comment('密码');
            $table->integer('roleId')->comment('角色类型(1-普通管理员)预留字段')->unsigned()->default(1);
            $table->integer('delete')->comment('是否删除 1-是, 0-否');
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
        //
        Schema::drop('admin_user');
    }
}
