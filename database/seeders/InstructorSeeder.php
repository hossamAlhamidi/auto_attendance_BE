<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $instructors = [];
        $faker = Faker::create();

        foreach (range(1,15) as $instructor) {
            try {
                $name = $faker->firstName();
                $instructor = [
                    'instructor_id' => random_int(1000000,9999999),
                    'instructor_name' => $name . ' ' . $faker->lastName(),
                    'email' => $name . "gmail.com",
                    'phone_number' => '05' . random_int(10000000,99999999),
                    'is_admin' => random_int(0,1),
                    'password' => bcrypt(123),
                    'remember_token' => bcrypt(123),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('instructors')->insert($instructor);
                // $instructors[] = $instructor;
            } catch (\Throwable $th) {
                continue;
            }
        }

        // DB::table('instructors')->insert($instructor);
    }
}
