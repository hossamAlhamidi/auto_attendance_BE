<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Models\Instructor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\InstructorRegisteration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;;
use Illuminate\Support\Facades\Auth;

class InstructorAuthController extends Controller
{

    // public function index()
    // {
    //      return Instructor::select('instructor_id', 'instructor_name', 'email','phone_number')->get();
    // }
    public function register(request $request)
    {
        $var = $request->validate([
            'instructor_id' => 'required|string|unique:instructors,instructor_id|max:12',
            'instructor_name' => 'required|string',
            'email' => 'email|unique:instructors,email',
            'phone_number' => 'string|nullable',
            // 'password' => 'required|string|confirmed'
        ]);

        // create random password
        $password = Str::random(10);

        // sending email to the user
        if(Instructor::where('instructor_id', $var['instructor_id'])->first())
        {
            return response()->json(['message' => 'Instructor already exist'], 404);
        }

        $email = [
            'body' => 'This is your password: ' . $password ,
            'name' => $var['instructor_name']
        ];
        $send_email = Mail::to($var['email'])->send(new InstructorRegisteration($email));

        //create the instructor
        if($send_email){
            try {
                Instructor::create([
                    'instructor_id' => $var['instructor_id'],
                    'instructor_name' => $var['instructor_name'],
                    'email' => $var['email'],
                    'phone_number' => $var['phone_number'],
                    'password' =>  bcrypt($password)
                ]);

                // $token = $instructor->createToken('instructor_token', ['instructor'])->plainTextToken;

                // formlate the response massage
                $response = [
                    'instructor_id' => $var['instructor_id'],
                    'instructor_name' => $var['instructor_name'],
                    'email' => $var['email'],
                    'phone_number' => $var['phone_number']
                ];
                return response($response, 201);

            } catch (\Throwable $th) {
                return response(['message' => 'Wrong Email'], 404);
            }



            // way 1
            // $email = [
            //     'body' => 'This is your password: ' . $password ,
            //     'name' => $response['instructor_name']
            // ];
            // try {
            // } catch (\Throwable $th) {
            //     return response('Something went wrong');
            // }

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

        // $token = $instructor->createToken('instructor_token')->plainTextToken;

        return response('Something went wrong', 404);
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

        if($instructor->is_admin == 0){
            $token = $instructor->createToken('instructor_token', ['instructor'])->plainTextToken;

        } else {
            $token = $instructor->createToken('instructor_token', ['admin'])->plainTextToken;
        }

        $response = [
            'token' => $token,
            'instructor_id' => $instructor['instructor_id'],
            'instructor_name' => $instructor['instructor_name'],
            'email' => $instructor['email'],
            'phone_number' => $instructor['phone_number'],
            'is_admin' => $instructor['is_admin']
        ];

        return response($response, 200);
    }

    public function logout(request $request)
    {
    //     $user = Auth::guard('sanctum')->user();
    //    echo $user->rememberToekn;
        $request->user()->currentAccessToken()->delete();
        // $user->currentAccessToken()->delete();
        return [
            'message' => 'logged out'
        ];
    }

    public function forgetPassword($instructor_id)
    {
        // create randomed password
        $password = Str::random(10);

        // sending email to the user
        if(!$instructor = instructor::where('instructor_id', $instructor_id)->first())
        {
            return response(['message' => 'No instructor with this ID'], 404);
        }
        try {
            $email = [
                'body' => 'This is your new password: ' . $password ,
                'name' => $instructor['instructor_name']
            ];
            $send_email = Mail::to($instructor['email'])->send(new InstructorRegisteration($email));
        } catch (\Throwable $th) {
            return response(['message' => 'Could not send Email'], 404);
        }

        if($send_email)
        {
            try {
                instructor::where('instructor_id', $instructor_id)->update(['password' => bcrypt($password)]);
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
