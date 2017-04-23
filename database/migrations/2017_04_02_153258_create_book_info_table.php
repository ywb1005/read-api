<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('book_info', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->integer('bookId')->comment('书籍id');
            $table->integer('nodeId')->comment('章节id');
            $table->text('nodeName')->comment('章节名称');
            $table->text('nodeImg')->comment('章节配图');
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
        Schema::drop('book_info');
    }
}
