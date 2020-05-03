<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house', function (Blueprint $table) {
            $table->integerIncrements('house_id')->comment('楼盘id');
            $table->string('name')->default('')->comment('楼盘名称');
            $table->string('desc')->default('')->comment('楼盘简介');
            $table->longText('content')->comment('详情');
            $table->string('household')->default(0)->comment('住户数量');
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->string('house_region')->default('[]')->comment('省市区选择的数据列表');
            $table->integer('region_id')->default(0)->comment('区域关联id');
            $table->integer('author_id')->default(0)->comment('作者id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('house_image', function (Blueprint $table)  {
            $table->unsignedInteger('house_id');
            $table->unsignedInteger('image_id');
            $table->timestamps();
            $table->index(['house_id','image_id'], 'house_image_id');

            $table->foreign('house_id')
                ->references('house_id')
                ->on('house')
                ->onDelete('cascade');
            $table->primary(['house_id', 'image_id'],
                'house_image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house');
        Schema::dropIfExists('house_image');
    }
}
