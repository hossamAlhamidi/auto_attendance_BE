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
        $instructors = [];
        $faker = Faker::create();

        foreach (range(1,10) as $instructor) {
            $password = Str::random(10);
            $first_name = $faker->firstName();
            $instructor = [
                'instructor_id' => $faker->unique()->randomNumber,
                'instructor_name' => $first_name . ' ' . $faker->lastName(),
                'email' => $faker->email(),
                'phone_number' => $faker->phoneNumber(),
                'is_admin' => random_int(0,1),
                'password' => bcrypt($password),
                'remember_token' => $password, 
                'created_at' => now(),
                'updated_at' => now()
            ];
            $instructors[] = $instructor; 
        }

        DB::table('instructors')->insert($instructors);
    }
}
