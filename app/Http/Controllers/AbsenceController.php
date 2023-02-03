<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absence;
use App\Models\Course;
use App\Models\Section;
use App\Models\Student_Section;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $var = $request->validate([
            'student_id'=>'required|max:9',
            'section_id'=>'required|max:7'
        ]);
        
        $student = DB::table('student__sections')->where('student_id',$var['student_id'])->where('section_id',$var['section_id'])->first();

        if(!$student){
            return response(
                ['message' => 'Students is not registered on this section'],
                404
            );
        }

        $today = Carbon::now()->format('Y-m-d');
        try{
            $course_hours = DB::table('sections')->join('courses', 'sections.course_id', '=', 'courses.course_id')->
                    where('section_id', $student->section_id)->select('course_hours')->first()->course_hours;

            $number_of_absence = Absence::where('student_id', $student->student_id)->count();
            $absence_percentage = $this->calculatePercentages($course_hours, $number_of_absence);

            DB::table('absences')->insert([
                'student_id' => $student->student_id,
                'section_id' => $student->section_id,
                'absence_date' => $today,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            DB::table('student__sections')->where('student_id',$var['student_id'])->where('section_id',$var['section_id'])->
                    update(['number_of_absence' => $number_of_absence, 'absence_percentage' => $absence_percentage]);
        }
        catch (\Throwable $th) {
            return response(['message'=>$th], 400);
        }

        return response(['massage' => 'absence is added'], 201);
    }

    public function multiAbsence(Request $request)
    {
        $var = $request->validate([
            'section_id'=>'required|max:7',
            'students_ids'=>'required|array'

        ]);

        $section_id = $var['section_id'];
        $students_ids = $var['students_ids'];
        
        $students = DB::table('student__sections')->where('section_id', $section_id)->whereIn('student_id', $students_ids)->get();

        if($students->isEmpty())
        {
            return response()->json(
                ['message' => 'Student is not registered on this section'],
                404
            );
        }

        $today = Carbon::now()->format('Y-m-d');

        $course_hours = DB::table('sections')->join('courses', 'sections.course_id', '=', 'courses.course_id')->
                where('section_id',$var['section_id'])->select('course_hours')->first()->course_hours;

        foreach($students as $student)
        {
            $number_of_absence = Absence::where('student_id', $student->student_id)->count();
            $absence_percentage = $this->calculatePercentages($course_hours, $number_of_absence);

            try {
                DB::table('absences')->insert([
                    'student_id' => $student->student_id,
                    'section_id' => $student->section_id,
                    'absence_date' => $today,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Throwable $th) {
                continue;
            }

            DB::table('student__sections')->where('student_id', $student->student_id)->where('section_id', $student->section_id)->
                    update(['number_of_absence' => $number_of_absence, 'absence_percentage' => $absence_percentage]);
        }

        return response(['massage' => 'absences is added'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($student_id)
    {
        
        $result = DB::select(DB::raw("SELECT * FROM absences,`sections` where sections.section_id = absences.section_id and student_id = $student_id "));
        return $result;
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
    public function destroy(Request $request)
    {
      $var=  $request->validate([
            'student_id'=>'required',
            'section_id'=>'required',
            'absence_date'=>'required|date'
      ]);
      
        $absence = DB::table('absences')->where('student_id', $var['student_id'])->where('section_id',$var['section_id'])->where('absence_date',$var['absence_date']);
        if($absence->get()->isEmpty())
        {
            return response(['massage' => 'this Absence info is not found'], 404);
        }
        $absence->delete();

        $student = DB::table('student__sections')->where('student_id',$var['student_id'])->where('section_id',$var['section_id'])->first();
        $course_hours = DB::table('sections')->join('courses', 'sections.course_id', '=', 'courses.course_id')->
                    where('section_id',$var['section_id'])->select('course_hours')->first()->course_hours;

        $number_of_absence = Absence::where('student_id', $student->student_id)->count();
        $absence_percentage = $this->calculatePercentages($course_hours, $number_of_absence);

        DB::table('student__sections')->where('student_id',$var['student_id'])->where('section_id',$var['section_id'])->
                update(['number_of_absence' => $number_of_absence, 'absence_percentage' => $absence_percentage]);

        return response(['massage' => 'Absence is deleted'], 200);
    }

    public function multiDelete(Request $request)
    {
        $var = $request->validate([
            'students_ids'=>'required|array',
            'section_id'=>'required|max:7',
            'absence_date'=>'required|date'
        ]);

        $section_id = $var['section_id'];
        $students_ids = $var['students_ids'];
        
        $students = DB::table('student__sections')->where('section_id', $section_id)->whereIn('student_id', $students_ids)->get();

        if($students->isEmpty())
        {
            return response()->json(
                ['message' => 'Students is not registered on this section'],
                404
            );
        }

        $absences = DB::table('absences')->where('absence_date',$var['absence_date'])->where('section_id', $section_id)->whereIn('student_id', $students_ids);
        if($absences->get()->isEmpty())
        {
            return response(
                ['massage' => 'this Absence info is not found'],
                404
            );
        }
        $absences->delete();

        $course_hours = DB::table('sections')->join('courses', 'sections.course_id', '=', 'courses.course_id')->
                where('section_id',$var['section_id'])->select('course_hours')->first()->course_hours;

        foreach($students as $student)
        {
            $number_of_absence = Absence::where('student_id', $student->student_id)->count();
            $absence_percentage = $this->calculatePercentages($course_hours, $number_of_absence);

            DB::table('student__sections')->where('student_id', $student->student_id)->where('section_id', $student->section_id)->
                    update(['number_of_absence' => $number_of_absence, 'absence_percentage' => $absence_percentage]);
        }

        return response(['massage' => 'absences is added'], 201);
    }

    public function AbsenceHistory($section_id, $day)
    {
        // $all_students = Student_Section::where('section_id', $section_id)->get();
        $all_students = DB::table('student__sections')->join('students', 'student__sections.student_id', '=', 'students.student_id')->where('section_id', $section_id)->select('student__sections.student_id', 'student_name')->get();
        // $all_absence_students = Absence::where('section_id', $section_id)->where('absence_date', $day)->get();
        $all_absence_students = DB::table('absences')->where('section_id', $section_id)->where('absence_date', $day)->get();

        // return $all_absence_students;

        if($all_students->isEmpty())
        {
            return response()->json(['massage' => 'No students in this section'], 400);
        }

        $students = [];
        $absence = false;
        $var = [];

        foreach($all_students as $student)
        {
            if($all_absence_students->isNotEmpty())
            {
                foreach ($all_absence_students as $absence_student) 
                {
                    if($student->student_id == $absence_student->student_id)
                    {
                        $absence = true;
                    }

                    $var = [
                        'student_id' => $student->student_id,
                        'student_name' => $student->student_name,
                        'absence' => $absence
                    ];

                }
                $students[] = $var;
            } else{
                $var = [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'absence' => $absence
                ];
                $students[] = $var;
            }
            $absence = false;
        }
        return response()->json($students, 200);
        // return $students;
    }

    public function calculatePercentages($course_hours, $number_of_absence)
    {
        $number_of_weeks = 10;
        if ($course_hours == 4 || $course_hours == 3)
        {
            $number_of_class_in_week = 3; 
            return ($number_of_absence / ($number_of_class_in_week * $number_of_weeks)) / 100.0;

        }elseif ($course_hours == 2) {
            $number_of_class_in_week = 1; 
            return ($number_of_absence / ($number_of_class_in_week * $number_of_weeks)) / 100.0;
        }else {
            $number_of_class_in_week = 0;
             return ($number_of_absence / ($number_of_class_in_week * $number_of_weeks)) / 100.0;
        }
    }
}
