<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->integer('userId')->comment('评论人');
            $table->text('comment')->comment('评论内容');
            $table->integer('bookId')->comment('书籍id');
            $table->integer('delete')->comment('是否删除 1-是, 0-否');
            $table->integer('createTime')->comment('创建时间')->unsigned(true)->default(1);
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
        Schema::drop('comments');
    }
}
