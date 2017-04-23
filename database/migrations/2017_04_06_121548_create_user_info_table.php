<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_info', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->integer('userId')->comment('用户id')->default(1);
            $table->integer('age')->comment('年龄');
            $table->integer('gender')->comment('性别(0-未设置, 1-男, 2-女)')->default(0);
            $table->text('collectBook')->comment('收藏书籍(书籍id每个之间用英文逗号隔开)');
            $table->text('hobby')->comment('爱好');
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
        Schema::drop('user_info');
    }
}
