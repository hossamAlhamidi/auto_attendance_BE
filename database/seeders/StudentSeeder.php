<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [];
        $faker = Faker::create();

        foreach (range(1,10) as $student) {
            $password = Str::random(10);
            $student = [
                'student_id' => '44'. random_int(1,9) . '10' . random_int(1000,9999),
                'student_name' => $faker->firstName() . ' ' . $faker->lastName(),
                'email' => $faker->email(),
                'phone_number' => $faker->phoneNumber(),
                'password' => $password,
                'remember_token' => $password, 
                'created_at' => now(),
                'updated_at' => now()
            ];
            $students[] = $student; 
        }

        DB::table('students')->insert($students);
    }
}
