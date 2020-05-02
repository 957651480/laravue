<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->default('')->comment('用户名');
            $table->string('display_name')->default('')->comment('显示名');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('password')->default('')->comment('密码');
            $table->string('nickName')->default('')->comment('微信nickname');
            $table->string('open_id')->default('')->comment('微信openId');
            $table->string('user_region')->default('[]')->comment('省市区选择的数据列表');
            $table->integer('city_id')->default(0)->comment('城市id');
            $table->string('avatarUrl')->default('')->comment('微信头像');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
