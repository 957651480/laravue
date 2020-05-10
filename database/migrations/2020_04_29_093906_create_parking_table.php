<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking', function (Blueprint $table) {
            $table->integerIncrements('parking_id')->comment('车位id');
            $table->string('code')->default('')->comment('车位编号');
            $table->tinyInteger('size_id')->default(0)->comment('车位大小：10 小车位,20 大车位');
            $table->tinyInteger('type_id')->default(0)->comment('车位类型：10 认筹,20 竞拍');
            $table->integer('parking_recommend')->default(20)->comment('车位推荐10 推荐车位 20 否');
            $table->tinyInteger('parking_area_id')->default(0)->comment('车位区域id');
            $table->tinyInteger('parking_floor_id')->default(0)->comment('车位楼层id');
            $table->decimal('price')->default(0.00)->comment('价格');
            $table->integer('house_id')->default(0)->comment('关联楼盘id');
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->integer('author_id')->default(0)->comment('作者id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('parking_area', function (Blueprint $table) {
            $table->integerIncrements('parking_area_id')->comment('车位区域id');
            $table->string('name')->default('')->comment('区域名称');
            $table->integer('sort')->default(0)->comment('排序');
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->integer('author_id')->default(0)->comment('作者id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('parking_floor', function (Blueprint $table) {
            $table->integerIncrements('parking_floor_id')->comment('车位楼层id');
            $table->string('name')->default('')->comment('区域名称');
            $table->integer('sort')->default(0)->comment('排序');
            $table->integer('city_id')->default(0)->comment('城市id');
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
        Schema::dropIfExists('parking_area');
        Schema::dropIfExists('parking');
        Schema::dropIfExists('parking_floor');
    }
}
