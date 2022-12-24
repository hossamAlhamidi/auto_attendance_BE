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
        Schema::create('sections', function (Blueprint $table) {
            // $table->id();
            $table->string('section_id')->primary();
            $table->string('course_id');
            $table->foreign('course_id')->references('course_id')->on("courses")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('instructor_id')->nullable();
            // $table->primary(['course_id','instructor_id']);
            $table->foreign('instructor_id')->references('instructor_id')->on("instructors")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('instructor_name')->nullable();
            $table->string('classroom')->nullable();
            $table->json('time')->nullable();
            $table->string('type');
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
        Schema::dropIfExists('sections');
    }
};
