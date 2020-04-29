<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->integerIncrements('region_id')->comment('地区id');
            $table->string('name')->default('')->comment('名称');
            $table->integer('parent_id')->default(0)->comment('父id');
            $table->integer('level')->default(1)->comment('层级');
            $table->integer('sort')->default(0)->comment('排序');
            $table->integer('show')->default(10)->comment('10显示 20隐藏');
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
        Schema::dropIfExists('regions');
    }
}
