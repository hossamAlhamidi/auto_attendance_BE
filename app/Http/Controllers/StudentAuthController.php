<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
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
            'password' => 'required|string' //|confirmed
        ]);

        //create the student 
        $student = Student::create([
            'student_id' => $var['student_id'],
            'student_name' => $var['student_name'],
            'email' => $var['email'],
            'phone_number' => $var['phone_number'],
            'password' => bcrypt($var['password'])
        ]);

        // $token = $student->createToken('studnet_token')->plainTextToken;

        $response = [
            'student_id' => $student['student_id'],
            'student_name' => $student['student_name'],
            'email' => $student['email'],
            'phone_number' => $student['phone_number']
        ];

        return response($response, 201);
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
        // auth()->student()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }
}
