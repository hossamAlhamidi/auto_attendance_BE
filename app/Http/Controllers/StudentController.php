<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Student::All();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $request->validate([
            'student_id'=>'required|max:9',
            // 'classroom'=>'required',
            // 'time'=>'required'
         ]);
        $student = Student::Where('student_id',$request->student_id)->get();
        if(count($student)>0){
            return $student;
        }
        else {
            return response([
                'message'=>'No found Student by This ID'
            ],401);
        }
    }

    public function showGet($id)
    {
        
        $student = Student::Where('student_id',$id)->get();
        if(count($student)>0){
            return $student;
        }
        else {
            return response([
                'message'=>'No found Student by This ID'
            ],401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated_id = htmlspecialchars($id);
        $student = Student::Where('student_id',$validated_id)->first();
        if(count($student->get())==0){
            return response([
                'message'=>'No found Student by This ID'
            ],401);
        }
        // print_r($request->all()) ;
        $student->update($request->all());
        // return Student::Where('student_id',$request->student_id)->get();
        //  return Response([$student],201);
        $response = [
            'student_id' => $student['student_id'],
            'student_name' => $student['student_name'],
            'email' => $student['email'],
            'phone_number' => $student['phone_number']
        ];

        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = DB::table('students')->where('student_id', $id);
        if(!$student->first())
        {
            return response(['message' => 'Student with this ID not found'], 404);
        }
        $student->delete();
        return response(['message' => 'Student is deleted'], 200);
    }
}
