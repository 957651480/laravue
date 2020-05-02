<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->integerIncrements('file_id');
            $table->string('filename')->default('')->comment('文件名称');
            $table->string('source_filename')->default('')->comment('源文件名');
            $table->string('extension')->default('')->comment('文件名扩展名');
            $table->mediumInteger('size')->default(0)->comment('文件大小');
            $table->string('mime_type')->default('')->comment('文件格式');
            $table->integer('author_id')->default(0)->comment('作者id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
