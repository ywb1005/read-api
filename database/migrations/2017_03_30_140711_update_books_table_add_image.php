<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBooksTableAddImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('books', function (Blueprint $table){
            $table->text('image')->comment('书籍信息图');
            $table->text('downUrl')->comment('全书下载地址');
            $table->integer('delete')->comment('是否删除');
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
        Schema::table('books', function (Blueprint $table){
            $table->dropColumn('image');
            $table->dropColumn('downUrl');
            $table->dropColumn('delete');
        });
    }
}
