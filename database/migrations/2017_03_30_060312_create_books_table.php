<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create books table
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->string('name')->comment('书名');
            $table->string('category')->comment('书籍类别');
            $table->string('abstract', 280)->comment('书籍摘要');
            $table->string('author', 50)->comment('书籍作者');
            $table->text('keyword')->comment('书籍关键词');
            $table->boolean('isMember')->comment('是否会员可见 1-是, 0-否')->unique();
            $table->boolean('isNew')->comment('是否新书上架 1-是, 0-否');
            $table->boolean('isHot')->comment('是否热门书籍 1-是, 0-否');
            $table->integer('weight')->comment('书籍权重')->unsigned(true)->default(1);
            $table->integer('createUser')->comment('书籍创建用户')->default(1);
            $table->integer('updateUser')->comment('书籍更新用户')->default(1);
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
        //dron books table
        Schema::drop('books');
    }
}
