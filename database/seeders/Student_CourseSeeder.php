<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Student_CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $student_courses = [];
        // $students = DB::table('students')->get();
        // $courses = DB::table('courses')->get();

        // foreach (range(1,10) as $student_course) {

        //     $student = $students->random();
        //     $course = $courses->random();

        //     $student_course = [
        //         'student_id' => $student->student_id,
        //         'course_id' => $course->course_id,
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ];
        //     $student_courses[] = $student_course;

        //     $students->where('student_id',$student->student_id)->keyBy('remove');
        //     $students->forget('remove');
        //     $courses->where('course_id',$course->course_id)->keyBy('remove');
        //     $courses->forget('remove');
        // }

        // DB::table('student_courses')->insert($student_courses);
    }
}
