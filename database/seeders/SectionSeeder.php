<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [];
        $type = ['lucture', 'lap', 'tutorial'];
        $courses = DB::table('courses')->get();
        $instructors = DB::table('instructors')->get();

        foreach (range(1,10) as $section) {
            
            $course = $courses->random();
            $instructor = $instructors->random();

            $section = [
                'section_id' => random_int(10000,99999),
                'course_id' => $course->course_id,
                'course_name' => $course->course_name,
                'instructor_id' => $instructor->instructor_id,
                'instructor_name' => $instructor->instructor_name,
                'classroom' => random_int(1,99),
                'time' => NULL, 
                'type' => Arr::random($type, 1), 
                'created_at' => now(),
                'updated_at' => now()
            ];
            $sections[] = $section; 
        }

        DB::table('sections')->insert($sections);
    }
}
