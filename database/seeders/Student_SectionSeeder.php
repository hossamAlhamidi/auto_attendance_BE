<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Student_SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student_sections = [];
        $students = DB::table('students')->get();
        $sections = DB::table('sections')->get();

        foreach (range(1,5) as $student_section) {
            
            $student = $students->random();
            $section = $sections->random();

            $student_section = [
                'student_id' => $student->student_id,
                'section_id' => $section->section_id,
                'absence_percentage' => random_int(1,25), 
                'number_of_absence' => random_int(1,10), 
                'created_at' => now(),
                'updated_at' => now()
            ];
            $student_sections[] = $student_section; 
        }

        DB::table('student__sections')->insert($student_sections);
    }
}
