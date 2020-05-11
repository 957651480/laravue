<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction', function (Blueprint $table) {
            $table->integerIncrements('auction_id')->comment('竞拍id');
            $table->string('title')->default('')->comment('资讯标题');
            $table->string('desc')->default('')->comment('资讯简介');
            $table->longText('content')->comment('详情');
            $table->decimal('start_price')->default(0.00)->comment('起拍价');
            $table->unsignedInteger('auction_recommend')->default(20)->comment('推荐10  20 否');
            $table->unsignedInteger('start_time')->default(0)->comment('开始时间');
            $table->unsignedInteger('end_time')->default(0)->comment('结束时间');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('parking_id')->default(0)->comment('车位id');
            $table->unsignedInteger('city_id')->default(0)->comment('城市id');
            $table->unsignedInteger('author_id')->default(0)->comment('作者id');
            $table->unsignedInteger('status_id')->default(10)->comment('状态10未开始,20进行中,30已结束');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('auction_image', function (Blueprint $table)  {
            $table->unsignedInteger('auction_id');
            $table->unsignedInteger('image_id');
            $table->timestamps();
            $table->index(['auction_id','image_id'], 'auction_image_id');

            $table->foreign('auction_id')
                ->references('auction_id')
                ->on('auction')
                ->onDelete('cascade');
            $table->primary(['auction_id', 'image_id'],
                'auction_image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auction');
        Schema::dropIfExists('auction_image');
    }
}
