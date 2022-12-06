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
        Schema::create('student__sections', function (Blueprint $table) {
             // $table->id();
             $table->string('student_id');
             $table->foreign('student_id')->references('student_id')->on("students")->cascadeOnDelete()->cascadeOnUpdate();
             $table->string('section_id');
             $table->foreign('section_id')->references('section_id')->on("sections")->cascadeOnDelete()->cascadeOnUpdate();
             $table->primary(['student_id','section_id']);
             $table->double('absence_percentage')->default(0);
             $table->double('number_of_absence')->default(0);
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
        Schema::dropIfExists('student__sections');
    }
};
