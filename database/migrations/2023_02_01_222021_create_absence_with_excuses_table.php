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
        Schema::create('absence_with_excuses', function (Blueprint $table) {
            $table->string('student_id');
            $table->foreign('student_id')->references('student_id')->on("students")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('section_id');
            $table->foreign('section_id')->references('section_id')->on("sections")->cascadeOnDelete()->cascadeOnUpdate();
            $table->date("absence_date");
           //  ->default(Carbon::now()->format('Y-m-d'));
            $table->string('reason')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
            $table->primary(['student_id','section_id','absence_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absence_with_excuses');
    }
};
