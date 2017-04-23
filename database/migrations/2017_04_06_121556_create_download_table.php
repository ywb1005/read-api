<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('download', function (Blueprint $table) {
            $table->increments('id')->comment('自增id');
            $table->integer('userId')->comment('用户id')->default(1);
            $table->integer('bookId')->comment('书籍id');
            $table->integer('createTime')->comment('下载时间')->unsigned(true)->default(1);
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
        Schema::drop('download');
    }
}
