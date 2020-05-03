<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->integerIncrements('banner_id')->comment('轮播图id');
            $table->string('title')->default('')->comment('标题');
            $table->integer('image_id')->default(0)->comment('文件id');
            $table->integer('type_id')->default(10)->comment('10首页banner 20 楼盘banner');
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->integer('sort')->default(0)->comment('排序');
            $table->integer('show')->default(10)->comment('10显示 20隐藏');
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
        Schema::dropIfExists('banner');
    }
}
