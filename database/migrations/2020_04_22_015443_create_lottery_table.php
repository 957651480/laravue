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
            $table->string('name')->default('')->comment('名字');
            $table->string('position')->default('[]')->comment('职位');
            $table->string('introduction')->default('')->comment('教师简介');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('lottery_prize', function (Blueprint $table) {
            $table->integerIncrements('lottery_prize')->comment('奖项id');
            $table->string('name')->default('')->comment('奖品名称');
            $table->integer('number')->default(0)->comment('数量');
            $table->integer('probability')->default(0)->comment('概率');
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
        Schema::dropIfExists('lottery_prize');
    }
}
