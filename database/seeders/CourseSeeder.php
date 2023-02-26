<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            [
                'course_id' => 'SWE 211',
                'course_name' => 'Introduction to Software Engineer',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'CSC 111',
                'course_name' => 'Computer Programing 1',
                'course_hours' => 4,
                'has_tutorial' => 1,
                'has_lab' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'CSC 113',
                'course_name' => 'Computer Programing 2',
                'course_hours' => 4,
                'has_tutorial' => 1,
                'has_lab' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'phy 103',
                'course_name' => 'General Physics 1',
                'course_hours' => 4,
                'has_tutorial' => 1,
                'has_lab' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'phy 104',
                'course_name' => 'General Physics 2',
                'course_hours' => 4,
                'has_tutorial' => 1,
                'has_lab' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'MATH244',
                'course_name' => 'Linear Algebra',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'MATH 106',
                'course_name' => 'INTEGRAL CALCULUS',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'CENX 303',
                'course_name' => 'Computer Communications & Networks',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'CSC 212',
                'course_name' => 'Data Structures',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'SWE 381',
                'course_name' => 'Web Application Development',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'SWE 312',
                'course_name' => 'Software Requirements Engineering',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'SWE 333',
                'course_name' => 'Software Quality Assurance',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'SWE 314',
                'course_name' => 'Software Security Engineering',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'SWE 321',
                'course_name' => 'Software Design & Architecture',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => 'SWE 482',
                'course_name' => 'Human-Computer Interaction',
                'course_hours' => 3,
                'has_tutorial' => 1,
                'has_lab' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        try {
            DB::table('courses')->insert($courses);
        } catch (\Throwable $th) {

        }
        // DB::table('courses')->insert($courses);
    }
}
