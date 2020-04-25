<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attends', function (Blueprint $table) {
            $table->integerIncrements('attend_id')->comment('报名id');
            $table->string('student_name')->default('')->comment('学生姓名');
            $table->string('grade')->default('')->comment('年级');
            $table->string('class')->default('')->comment('班级');
            $table->integer('time_id')->default(0)->comment('时间段id');
            $table->integer('course_id')->comment('课程id');
            $table->integer('user_id')->comment('用户id');
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
        Schema::dropIfExists('attends');
    }
}
