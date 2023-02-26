<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $startDate = Carbon::now()->subYears(1);
        $endDate = Carbon::now()->addMonths(1);

        $randomDate = $faker->dateTimeBetween($startDate, $endDate);
        $formattedDate = Carbon::parse($randomDate)->format('Y-m-d');

        // $absences = [];
        $students = DB::table('students')->get();
        $sections = DB::table('sections')->get();

        foreach (range(1,50) as $absence) {
            try {
                $student = $students->random();
                $section = $sections->random();

                $absence = [
                    'student_id' => $student->student_id,
                    'section_id' => $section->section_id,
                    'absence_date' => $formattedDate,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('absences')->insert($absence);
                // $absences[] = $absence;
            } catch (\Throwable $th) {
                continue;
            }

        }


    }
}
