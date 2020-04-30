<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->integerIncrements('house_id')->comment('楼盘id');
            $table->string('name')->default('')->comment('楼盘名称');
            $table->string('desc')->default('')->comment('楼盘简介');
            $table->string('content')->default('')->comment('详情');
            $table->string('household')->default(0)->comment('住户数量');
            $table->integer('city_id')->default(0)->comment('城市关联id');
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
        Schema::dropIfExists('houses');
    }
}
