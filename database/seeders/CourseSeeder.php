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
        $courses = [];
        $faker = Faker::create();

        foreach (range(1,5) as $course) {
            // $abbreviation = Str::random(3);
            $abbreviation = substr(str_shuffle(str_repeat(strtoupper("abcdefghijklmnopqrstuvwxyz"), 3)), 0, 3);
            $course = [
                'course_id' => $abbreviation . ' ' . random_int(100,999),
                'course_name' => 'This random course name',
                // 'abbreviation' => random_int(100,999),
                'course_hours' => random_int(1,3),
                'has_tutorial' => random_int(0,1),
                'has_lab' => random_int(0,1), 
                'created_at' => now(),
                'updated_at' => now()
            ];
            $courses[] = $course; 
        }

        DB::table('courses')->insert($courses);
    }
}
