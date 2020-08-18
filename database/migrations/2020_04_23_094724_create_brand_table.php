<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand', function (Blueprint $table) {
            $table->integerIncrements('id')->comment('id');
            $table->string('name')->default('')->comment('标题');
            $table->unsignedInteger('image_id')->default(0)->comment('图片id');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('show')->default(10)->comment('10显示 20隐藏');
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
        Schema::dropIfExists('brand');
    }
}
