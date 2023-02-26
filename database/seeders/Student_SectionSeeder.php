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
        // $student_sections = [];
        $students = DB::table('students')->get();
        $sections = DB::table('sections')->get();




        foreach (range(1,40) as $student_section) {

            $student = $students->random();
            $section = $sections->random();
            $number_of_absence = DB::table('absences')->where('student_id' , $student->student_id)->count();
            $percentage = 0;


            $course_hours = DB::table('sections')->join('courses', 'sections.course_id', '=', 'courses.course_id')->
                    where('section_id', $section->section_id)->select('course_hours')->first()->course_hours;
            $number_of_weeks = 10;
            if ($course_hours == 4 || $course_hours == 3)
            {
                $number_of_class_in_week = 3;
                $percentage = (($number_of_absence / ($number_of_class_in_week * $number_of_weeks))) * 100;

            }elseif ($course_hours == 2) {
                $number_of_class_in_week = 1;
                $percentage = (($number_of_absence / ($number_of_class_in_week * $number_of_weeks))) * 100;
            }else {
                // $number_of_class_in_week = 0;
                $percentage = 0;
            }

            try {
                $student_section = [
                    'student_id' => $student->student_id,
                    'section_id' => $section->section_id,
                    'absence_percentage' => $percentage,
                    'number_of_absence' => $number_of_absence,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('student__sections')->insert($student_section);
                // $student_sections[] = $student_section;
            } catch (\Throwable $th) {
                continue;
            }
        }

    }
}
