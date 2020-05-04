<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery', function (Blueprint $table) {
            $table->integerIncrements('lottery_id')->comment('转盘id');
            $table->string('title')->default('')->comment('资讯标题');
            $table->string('desc')->default('')->comment('资讯简介');
            $table->longText('content')->comment('详情');
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->integer('author_id')->default(0)->comment('作者id');
            $table->softDeletes();
            $table->timestamps();

        });
        Schema::create('lottery_image', function (Blueprint $table)  {
            $table->unsignedInteger('lottery_id');
            $table->unsignedInteger('image_id');
            $table->timestamps();
            $table->index(['lottery_id','image_id'], 'lottery_image_id');

            $table->foreign('lottery_id')
                ->references('lottery_id')
                ->on('lottery')
                ->onDelete('cascade');
            $table->primary(['lottery_id', 'image_id'],
                'lottery_image_id');
        });

        Schema::create('lottery_prize', function (Blueprint $table) {
            $table->integerIncrements('lottery_prize')->comment('奖项id');
            $table->string('name')->default('')->comment('奖品名称');
            $table->integer('number')->default(0)->comment('数量');
            $table->integer('probability')->default(0)->comment('概率');
            $table->integer('author_id')->default(0)->comment('作者id');
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
        Schema::dropIfExists('lottery');
        Schema::dropIfExists('lottery_image');
        Schema::dropIfExists('lottery_prize');
    }
}
