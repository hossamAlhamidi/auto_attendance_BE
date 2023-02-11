<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            // StudentSeeder::class,
            // InstructorSeeder::class,
            // CourseSeeder::class,
            // SectionSeeder::class,
            // AbsenceSeeder::class,
            // Student_sectionSeeder::class,
            // Student_CourseSeeder::class,
        ]);
    }
}
