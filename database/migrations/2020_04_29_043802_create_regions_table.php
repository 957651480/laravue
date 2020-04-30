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
            $table->string('short_name')->default('')->comment('短名称');
            $table->string('name')->default('')->comment('名称');
            $table->string('merger_name')->default('')->comment('合并名称');
            $table->integer('parent_id')->default(0)->comment('父id');
            $table->integer('level')->default(1)->comment('层级');
            $table->string('pinyin')->default('')->comment('拼音');
            $table->string('code')->default('')->comment('长途区号');
            $table->string('zip_code')->default('')->comment('邮编');
            $table->string('first')->default('')->comment('首字母');
            $table->string('lng')->default('')->comment('经度');
            $table->string('lat')->default('')->comment('纬度');
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
