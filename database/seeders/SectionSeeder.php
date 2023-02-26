<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

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
    $courses = DB::table('courses')->get();
    $instructors = DB::table('instructors')->get();

    $daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];

    foreach (range(1,15) as $sectionIndex) {

        $course = $courses->random();
        $instructor = $instructors->random();

        $section_id = random_int(10000,99999);

        $time = [];
        foreach ($daysOfWeek as $day) {
            // Set a random time for this day if this section has a class on this day
            $timeRange = "-";
            if (rand(0, 1) == 1) {
                $startTime = rand(8, 19); // start time between 8am and 7pm
                $endTime = rand($startTime + 1, 20); // end time between start time + 1 and 8pm
                $timeRange = str_pad($startTime, 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . '-' . str_pad($endTime, 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
            }
            $time[$day] = $timeRange;
        }

        $section = [
            'section_id' => $section_id,
            'course_id' => $course->course_id,
            'course_name' => $course->course_name,
            'instructor_id' => $instructor->instructor_id,
            'instructor_name' => $instructor->instructor_name,
            'classroom' => random_int(1,99),
            'time' => json_encode($time),
            'type' => 'lecture',
            'created_at' => now(),
            'updated_at' => now()
        ];
        // $sections[] = $section;
        DB::table('sections')->insert($section);

        // Add lab or tutorial sections if this course has them
        if ($course->has_tutorial) {
            $tutorial_id = $section_id+1;
            $section_tutorial = [
                'section_id' => $tutorial_id,
                'course_id' => $course->course_id,
                'course_name' => $course->course_name,
                'instructor_id' => $instructor->instructor_id,
                'instructor_name' => $instructor->instructor_name,
                'classroom' => random_int(1,99),
                'time' => json_encode($time),
                'type' => 'tutorial',
                'created_at' => now(),
                'updated_at' => now()
            ];
            DB::table('sections')->insert($section_tutorial);
            // $sections[] = $section_tutorial;
        }

        if ($course->has_lab) {
            $lab_id = $section_id + 2;
            $section_lab = [
                'section_id' => $lab_id,
                'course_id' => $course->course_id,
                'course_name' => $course->course_name,
                'instructor_id' => $instructor->instructor_id,
                'instructor_name' => $instructor->instructor_name,
                'classroom' => random_int(1,99),
                'time' => json_encode($time),
                'type' => 'lab',
                'created_at' => now(),
                'updated_at' => now()
            ];
            DB::table('sections')->insert($section_lab);
            // $sections[] = $section_lab;
        }
    }

    //  DB::table('sections')->insert($sections);
}
}
