<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderParkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_parking', function (Blueprint $table) {
            $table->integerIncrements('order_parking_id')->comment('订单车位id');
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->unsignedInteger('type_id')->comment('车位类型id');
            $table->string('house_id')->comment('楼盘id');
            $table->string('house_name')->comment('楼盘名称');
            $table->unsignedInteger('city_id')->default(0)->comment('城市id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户id');
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
        Schema::dropIfExists('order_parking');
    }
}
