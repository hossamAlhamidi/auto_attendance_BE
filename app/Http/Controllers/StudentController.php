<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Section;
use App\Models\Student_Section;
use Illuminate\Support\Facades\Hash;

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
            'student_id'=>'required|min:3|max:9',
            'instructor_id' => '',
            // 'classroom'=>'required',
            // 'time'=>'required'
         ]);

        $student_id = $request->student_id;
        $instructor_id = $request->instructor_id;

        $instructor = Instructor::where('instructor_id', $instructor_id)->first();

            if(!$instructor)
            {
                return response(['message' => 'There is no Instructor with this Instructor ID'], 404);
            }

            $student = Student::Where('student_id','like','%'.$student_id.'%')->get();
            if(count($student) == 0)
            {
                return response([
                    'message'=>'No found Student by This ID'
                ],401);
            }

            if ($instructor->is_admin == 1)
            {
                return response($student, 200);
            }
            else
            {
                // $student = (new SectionController)->FindStudentForInstructor($instructor_id, $student_id);
                // $student = DB::select(DB::raw("SELECT student_id,student_name,email,phone_number,section_id FROM students,`sections` where sections.instructor_id = $instructor_id and student_id = $student_id "));
                $sections = Section::Where('instructor_id',$instructor_id)->get('section_id');
                $sections_arr = [];
                foreach($sections as $section){
                    // echo $section->section_id;
                    array_push($sections_arr,$section->section_id);
                }
                $students_id = [];
                // foreach($sections_arr as $section_id){
                //     //  $student = DB::select(DB::raw("SELECT students.student_id,student_name,email,phone_number,section_id FROM students,`student__sections` where student__sections.student_id = $student_id and section_id = $section_id and  "));
                //     $student=DB::select(DB::raw("SELECT student_id FROM student__sections where student_id = $student_id and section_id = $section_id"));

                // }
                $student = DB::table('student__sections')->whereIn('section_id', $sections_arr)->get('student_id');
                foreach($student as $st){
                    array_push($students_id,$st->student_id);
                }

                // foreach( $students_id as $student_id){
                //   $student =  DB::select(DB::raw("SELECT student_id,student_name,email,phone_number FROM students where student_id = $student_id "));
                // }
                $student = DB::table('students')->whereIn('student_id', $students_id)->where('student_id','like','%'.$student_id.'%')->get();


            }
            // if(!$student->isEmpty())
            if(count($student)>0)
            {
                return response($student, 200);
            }
            else {
                return response([
                    'message'=>'No students results registered to you sections'
                ],401);
            }



        // $student = Student::Where('student_id',$request->student_id)->get();
        // if(count($student)>0){
        //     return $student;
        // }
        // else {
        //     return response([
        //         'message'=>'No found Student by This ID'
        //     ],401);
        // }
    }


    public function showGet($student_id, $instructor_id = 0)
    {
        if($instructor_id ==0)
        {
            $student = Student::Where('student_id',$student_id)->get(['student_id','student_name','email','phone_number','mac_address']);
            if(count($student)>0){
                return $student;
            }
            else {
                return response([
                    'message'=>'No found Student by This ID'
                ],401);
            }
        }
        // maybe else is not needed any more since we moved the logic to the function above and this function to get student info so i can update it later
        else
        {
            $instructor = Instructor::where('instructor_id', $instructor_id)->first();

            if(!$instructor)
            {
                return response(['message' => 'There is no Instructor with this Instructor ID'], 404);
            }

            $student = Student::Where('student_id',$student_id)->get();
            if(count($student) != 1)
            {
                return response([
                    'message'=>'No found Student by This ID'
                ],401);
            }

            if ($instructor->is_admin == 1)
            {
                return response($student, 200);
            }
            else
            {
                $student = (new SectionController)->FindStudentForInstructor($instructor->instructor_id, $student_id);
            }

            if(!$student->isEmpty()){
                return response($student, 200);
            }
            else {
                return response([
                    'message'=>'No found Student by This ID'
                ],401);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'student_id'=>'required',
            'student_name' => 'required',
            'email' => 'required|email',
            'mac_address'=>'required'

        ]);
        $student = Student::Where('student_id',$validatedData['student_id'])->first();
        if(is_null($student)){
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
            'phone_number' => $student['phone_number'],
            'mac_address' => $student['mac_address']
        ];

        return response($response, 201);
    }


    public function updatePassword(Request $request, $student_id)
    {
        $validated_id = htmlspecialchars($student_id);
        $student = Student::where('student_id',$validated_id)->first();
        if(!$student){
            return response([
                'message'=>'There is no Student by This ID'
            ],401);
        }

        $validatedData = $request->validate([
            'password' => 'sometimes|string|min:3',
        ]);

        if(array_key_exists('password', $validatedData)){
            // check if the old password provided is correct
            if (!Hash::check($request->old_password, $student->password)) {
                return response(['message' => 'Old password is not correct'], 401);
            }
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $student->where('student_id',$validated_id)->update($validatedData);
        return response(['message' => 'Student information updated successfully']);
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
