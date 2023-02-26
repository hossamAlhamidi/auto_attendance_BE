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
        // $students = [];
        $faker = Faker::create();

        foreach (range(1,15) as $student) {
            $id = '4' . random_int(3,4) . random_int(1,9) . '100' . random_int(100,999);
            try {
                $student = [
                    'student_id' => $id,
                    'student_name' => $faker->firstName() . ' ' . $faker->lastName(),
                    'email' => $id . "@student.ksu.edu.sa",
                    'phone_number' => '05' . random_int(10000000,99999999),
                    'mac_address' => $faker->macAddress,
                    'password' => bcrypt(123),
                    'remember_token' => bcrypt(123),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('students')->insert($student);
                // $students[] = $student;
            } catch (\Throwable $th) {
                continue;
            }
        }


    }
}
