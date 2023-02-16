<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\InstructorRegisteration;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentForgetPassword;
// use GuzzleHttp\Promise\Create;
// use Illuminate\Http\Controller\StudentController;

class StudentAuthController extends Controller
{
    public function register(request $request)
    {
        $var = $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'student_name' => 'required|string',
            'email' => 'string',
            'phone_number' => 'string',
            'mac_address' => 'required|string'
        ]);
        // return $var['mac_address'];

        // create random password
        $password = Str::random(10);


        if(Student::where('student_id', $var['student_id'])->first())
        {
            return response(['message' => 'Email already exist'], 404);
        }
        if(Student::where('email', $var['email'])->first())
        {
            return response(['message' => 'Email already exist'], 404);
        }


        // sending email to the user
        $email = [
            'body' => 'This is your password: ' . $password ,
            'name' => $var['student_name']
        ];
        $send_email = Mail::to($var['email'])->send(new InstructorRegisteration($email));

        //create the student
        if($send_email){
            try {
                $student = Student::create([
                    'student_id' => $var['student_id'],
                    'student_name' => $var['student_name'],
                    'email' => $var['email'],
                    'phone_number' => $var['phone_number'],
                    'mac_address' => $var['mac_address'],
                    'password' => bcrypt($password),
                ]);

                // $token = $student->createToken('studnet_token')->plainTextToken;

                $response = [
                    'student_id' => $student['student_id'],
                    'student_name' => $student['student_name'],
                    'email' => $student['email'],
                    'phone_number' => $student['phone_number']
                ];

                return response($response, 201);

            } catch (\Throwable $th) {
                return response(['message' => 'Wrong Email'], 404);
            }
        }

    }

    public function login(request $request)
    {
        $var = $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string'
        ]);

        // check if exist
        $student = Student::where('student_id', $var['student_id'])->first();
        if(!$student || !Hash::check($var['password'], $student->password)):
            return response(
                ['message' => 'ID or password is wrong'],
                401
            );
        endif;

        // $token = $student->createToken('studnet_token')->plainTextToken;

        $response = [
            'student_id' => $student['student_id'],
            'student_name' => $student['student_name'],
            'email' => $student['email'],
            'phone_number' => $student['phone_number']
        ];

        return response($response, 200);
    }

    public function logout(request $request)
    {
        // auth()->user()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }

    public function forgetPassword($student_id)
    {
        // create randomed password
        $password = Str::random(10);

        // sending email to the user
        if(!$student = Student::where('student_id', $student_id)->first())
        {
            return response(['message' => 'No student with this ID'], 404);
        }
        try {
            $email = [
                'body' => 'This is your new password: ' . $password ,
                'name' => $student['student_name']
            ];
            $send_email = Mail::to($student['email'])->send(new StudentForgetPassword($email));
        } catch (\Throwable $th) {
            return response(['message' => 'Could not send Email'], 404);
        }

        if($send_email)
        {
            try {
                Student::where('student_id', $student_id)->update(['password' => bcrypt($password)]);
            } catch (\Throwable $th) {
                return response(['message' => 'Something went Wrong'], 404);
            }
        }
        return response()->json(
            [
                'message' => 'Email sent'
            ],200);
    }
}
