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
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->integer('author_id')->default(0)->comment('作者id');
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
