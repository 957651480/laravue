<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->integerIncrements('course_id')->comment('课程id');
            $table->string('title')->default('')->comment('标题');
            $table->longText('content')->comment('详细介绍');
            $table->integer('image_id')->default(0)->comment('文件id');
            $table->unsignedInteger('start_time')->default(0)->comment('开始时间');
            $table->unsignedInteger('end_time')->default(0)->comment('开始时间');
            $table->string('address')->default('')->comment('地点');
            $table->string('attend_number')->default(0)->comment('报名人数');
            $table->string('number')->default(0)->comment('上课人数');
            $table->integer('category_id')->default(0)->comment('分类id');
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
        Schema::dropIfExists('courses');
    }
}
