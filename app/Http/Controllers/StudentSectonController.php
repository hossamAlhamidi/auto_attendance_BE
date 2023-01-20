<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use App\Models\Student_Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentSectonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Student_Section::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $section = $request->section_id;
        $var = $request->validate([
            // 'student_id' => Rule::unique('student__sections')->where(function ($query) { return $query->where('student_id',438)&&$query->where('section_id', 15); }),
            'student_id' => "required|max:9|unique:student__sections,student_id,NULL,id,section_id,$section",
            'section_id' => 'required|max:9',
        ]);
        $student = Student::where('student_id', $var['student_id'])->first();
        if (!$student) :
            return response(
                ['message' => 'No student found on this id'],
                401
            );
        endif;
        $student =  DB::insert("insert into student__sections (student_id, section_id) values ($var[student_id], $var[section_id])");




        return response($student, 201);
    }

    public function storeAll(Request $request)
    {

        $var = $request->validate([
            // 'student_id' => Rule::unique('student__sections')->where(function ($query) { return $query->where('student_id',438)&&$query->where('section_id', 15); }),
            'student_array'=>'required',
            'section_id'=>"required"
        ]);

        $students = [];

        // $message = [
        //     "message"=>"All students are added"
        // ];
        $wrong_id = [];
        foreach ($request->student_array as $id) {
            $student = [
                "student_id"=>$id,
                "section_id"=>$var['section_id']
            ];
            $students[] =$student;
            try{
                DB::table("student__sections")->insert($student);
            }
            catch (\Throwable $th) {
                //  $message['message']="$student[student_id] is not found or maybe duplicate\n";
                array_push($wrong_id,$student["student_id"]);
            }
        }
         if(count($wrong_id)>0){
            $string= implode(",",$wrong_id);
            return response([
                "message"=>$string." these students id are duplicate or not found,the rest are added if there is any"
            ],401);
         }else{
          return response([
                "message"=>"All students are added"
            ],201);
         }
      
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showStudentsSections($student_id)
    {
        // $sections = Student_Section::where('student_id', $student_id)->get(); 

        // foreach ($sections as $section) {

        // }
        // $respons = [
        //     'sections' => $sections 
        // ];
        // return response($respons, 200);

        // $result = DB::select( DB::raw("SELECT * FROM sections,`student__sections` where sections.section_id = student__sections.section_id and student_id = $student_id ") );
        $result = DB::select(DB::raw("SELECT sections.section_id, `course_id`, `course_name`, instructors.instructor_name, instructors.email, `classroom`, `time`, `type`, `absence_percentage`, `number_of_absence` FROM sections,`student__sections`,`instructors` where sections.section_id = student__sections.section_id and sections.instructor_id = instructors.instructor_id and student_id = $student_id "));

        return $result;
    }


    // public function showInstructorSections($instructor_id)
    // {
    //     $result = DB::select( DB::raw("SELECT section_id, `course_id`, `instructor_name`, `classroom`, `time` FROM sections where instructor_id = $instructor_id ") );
    //     return $result;
    // }

    // public function showStudents($section_id)
    // {
    //     $studnets = Student_Section::where('section_id', $section_id)->get(['student_id']); 
    //     return response([$studnets], 200);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $var=  $request->validate([
            'student_id'=>'required',
            'section_id'=>'required',
        ]);

        // $student = Student_Section::where('student_id', $var['student_id'])->where('section_id', $var['section_id'])->first();
        $student = DB::table('student__sections')->where('student_id', $var['student_id'])->where('section_id', $var['section_id']);
        
        if(!$student->first())
        {
            return response()->json(['massage' => 'There is no student with this ID in this section'], 400);
        }
        
        $student->delete();

        return response()->json(['massage' => 'Delete Success'], 200);
    }

    public function sectionStudentsList($section_id)
    {
        //     return Student::
        // join('student__sections', 'student__sections.student_id', '=', 'students.student_id')
        // ->get();
        // $result = DB::statement('select * from students');
        $result = DB::select(DB::raw("SELECT students.student_id, students.student_name, students.email, students.phone_number, section_id, absence_percentage, number_of_absence FROM students,`student__sections` where students.student_id = student__sections.student_id and section_id = $section_id "));
        return $result;

        // ->where('countries.country_name', $country)
    }
}
