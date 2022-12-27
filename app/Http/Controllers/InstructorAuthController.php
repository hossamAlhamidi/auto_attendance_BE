<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Instructor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\InstructorRegisteration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class InstructorAuthController extends Controller
{

    public function index()
    {
       return Instructor::All();
    }
    public function register(request $request)
    {
        $var = $request->validate([
            'instructor_id' => 'required|string|unique:instructors,instructor_id',
            'instructor_name' => 'required|string',
            'email' => 'email',
            'phone_number' => 'string',
            // 'password' => 'required|string|confirmed'
        ]);

        $password = Str::random(10);

        //create the instructor 
        $instructor = Instructor::create([
            'instructor_id' => $var['instructor_id'],
            'instructor_name' => $var['instructor_name'],
            'email' => $var['email'],
            'phone_number' => $var['phone_number'],
            'password' =>  bcrypt($password)
        ]);

        // $token = $instructor->createToken('instructor_token')->plainTextToken;

        $response = [
            'instructor_id' => $instructor['instructor_id'],
            'instructor_name' => $instructor['instructor_name'],
            'email' => $instructor['email'],
            'phone_number' => $instructor['phone_number']
        ];

        if($instructor){

            // way 1
            $email = [
                'body' => 'This is your password: ' . $password ,
                'name' => $response['instructor_name']
            ];
            Mail::to($response['email'])->send(new InstructorRegisteration($email));

            // way 2
            // $to_name = $response['instructor_name'];
            // $to_email = $response['email'];
            // $data = array('name' => 'Automatic Attendance', 'body' => 'This is your password:' . $password);
            // Mail::send('emails.InstructorRegisteration', $data, function($message) use ($to_name, $to_email) 
            // {
            //     $message->to($to_email, $to_name)->subject('Registration Info');
            //     $message->from('a.attendancy@gmail.com', 'Automatic Attendance');
            // });
        }
        
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
            'instructor_id' => $instructor['instructor_id'],
            'instructor_name' => $instructor['instructor_name'],
            'email' => $instructor['email'],
            'phone_number' => $instructor['phone_number']
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
