<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->integerIncrements('information_id')->comment('资讯id');
            $table->string('title')->default('')->comment('资讯标题');
            $table->string('desc')->default('')->comment('资讯简介');
            $table->longText('content')->comment('详情');
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->integer('author_id')->default(0)->comment('作者id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('information_image', function (Blueprint $table)  {
            $table->unsignedInteger('information_id');
            $table->unsignedInteger('image_id');
            $table->timestamps();
            $table->index(['information_id','image_id'], 'information_image_id');

            $table->foreign('information_id')
                ->references('information_id')
                ->on('information')
                ->onDelete('cascade');
            $table->primary(['information_id', 'image_id'],
                'information_image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information');
        Schema::dropIfExists('information_image');
    }
}
