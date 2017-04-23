<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('upload', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->integer('userId')->comment('用户id')->default(1);
            $table->string('bookName')->comment('书籍名称');
            $table->string('bookAuthor')->comment('书籍作者');
            $table->string('bookUrl')->comment('书籍下载地址');
            $table->integer('createTime')->comment('上传时间')->unsigned(true)->default(1);
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
        Schema::drop('upload');
    }
}
