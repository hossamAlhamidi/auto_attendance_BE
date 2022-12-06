<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_courses', function (Blueprint $table) {
            // $table->id();
            $table->string('student_id');
            $table->foreign('student_id')->references('student_id')->on("students")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('course_id');
            $table->foreign('course_id')->references('course_id')->on("courses")->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['student_id','course_id']);
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
        Schema::dropIfExists('student_courses');
    }
};
