<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Instructor;
use Illuminate\Support\Facades\Hash;

class InstructorAuthController extends Controller
{
    public function register(request $request)
    {
        $var = $request->validate([
            'instructor_id' => 'required|string|unique:instructors,instructor_id',
            'instructor_name' => 'required|string',
            'email' => 'string',
            'phone_number' => 'int',
            'password' => 'required|string|confirmed'
        ]);

        //create the instructor 
        $instructor = Instructor::create([
            'instructor_id' => $var['instructor_id'],
            'instructor_name' => $var['instructor_name'],
            'email' => $var['email'],
            'phone_number' => $var['phone_number'],
            'password' => bcrypt($var['password'])
        ]);

        // $token = $instructor->createToken('studnet_token')->plainTextToken;

        $response = [
            'instructor' => $instructor
        ];

        return response($response, 201);
    }

    public function login(request $request)
    {
        $var = $request->validate([
            'instructor_id' => 'required|string',
            'password' => 'required|string'
        ]);

        // check if exist
        $instructor = Instructor::where('instructor_id', $var['instructor_id'])->first();
        if(!$instructor || !Hash::check($var['password'], $instructor->password)):
            return response(
                ['message' => 'ID or password is wrong'],
                401
            );
        endif;

        // $token = $instructor->createToken('studnet_token')->plainTextToken;

        $response = [
            'instructor' => $instructor
        ];

        return response($response, 200);
    }

    public function logout(request $request)
    {
        // auth()->instructor()->tokens()->delete();
        return [
            'message' => 'logged out'
        ];
    }
}
