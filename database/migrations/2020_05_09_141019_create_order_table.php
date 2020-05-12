<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->unsignedInteger('order_no')->comment('订单号');
            $table->unsignedInteger('pay_status')->comment('付款状态:10已付款 20 已付款');
            $table->string('out_trade_no')->comment('微信交易号');
            $table->unsignedInteger('order_source')->comment('订单来源10认筹订单,20竞拍订单');
            $table->unsignedInteger('order_status')->comment('订单状态10 进行中,20 已取消,30 已完成');
            $table->unsignedInteger('city_id')->default(0)->comment('城市id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户id');
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
        Schema::dropIfExists('order');
    }
}
