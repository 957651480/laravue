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
            $table->tinyInteger('type_id')->default(0)->comment('车位类型：10 小车位,20 大车位');
            $table->integer('house_id')->default(0)->comment('关联楼盘id');
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
        Schema::dropIfExists('parking');
    }
}
