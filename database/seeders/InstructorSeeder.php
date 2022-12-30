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

        foreach (range(1,5) as $instructor) {
            $password = Str::random(10);
            $instructor = [
                'instructor_id' => Str::random(5),
                'instructor_name' => $faker->firstName() . ' ' . $faker->lastName(),
                'email' => $faker->email(),
                'phone_number' => $faker->phoneNumber(),
                'is_admin' => random_int(0,1),
                'password' => $password,
                'remember_token' => $password, 
                'created_at' => now(),
                'updated_at' => now()
            ];
            $instructors[] = $instructor; 
        }

        DB::table('instructors')->insert($instructors);
    }
}
