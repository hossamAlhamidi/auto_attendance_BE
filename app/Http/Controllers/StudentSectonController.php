<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use App\Models\Student_Section;
use Illuminate\Support\Facades\DB;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSections($student_id)
    {
        return Student_Section::where('student_id', $student_id)->all();
    }

    public function showStudents($section_id)
    {
        return Student_Section::where('section_id', $section_id)->all();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sectionStudentsList($section_id){
    //     return Student::
    // join('student__sections', 'student__sections.student_id', '=', 'students.student_id')
    // ->get();
    // $result = DB::statement('select * from students');
    $result = DB::select( DB::raw("SELECT * FROM students,`student__sections` where students.student_id = student__sections.student_id and section_id = $section_id ") );
    return $result;
  
    // ->where('countries.country_name', $country)
    }
}
