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
    //    $student = DB::select(DB::raw("SELECT * FROM `student__sections` where section_id = $var[section_id] and student_id = $var[student_id] "));
        if(!$student){
            return response(
                ['message' => 'Student is not registered on this section'],
                404
            );
        }
        $now = now();
        $today = Carbon::now()->format('Y-m-d');
        try{
            // $absence= DB::insert("insert into absences (student_id, section_id, absence_date,created_at,updated_at) values ($var[student_id], $var[section_id],'$today','$now','$now')");
            // DB::insert("insert into absences (student_id, section_id, absence_date, created_at, updated_at) values ($var[student_id], $var[section_id],'$today','$now','$now')");
            DB::table('absences')->insert([
                'student_id' => $student->student_id,
                'section_id' => $student->section_id,
                'absence_date' => $today,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $student->number_of_absence = $student->number_of_absence + 1 ;
            DB::table('student__sections')->where('student_id',$var['student_id'])->where('section_id',$var['section_id'])->update(['number_of_absence' => $student->number_of_absence]);
        }
        catch (\Throwable $th) {
            return response(['message'=>$th], 400);
        }

        return response(['massage' => 'absence is added'], 201);
    }

    public function multiAbsence(Request $request)
    {
        // $var = $request->validate([
        //     'section_id'=>'required|max:7',
        //     'students_ids'=>'required'

        // ]);

        $section_id = $request->section_id;
        $students_ids = $request->student_id;
        
        $students = DB::table('student__sections')->where('section_id', $section_id)->whereIn('student_id', $students_ids)->get();

        if($students->isEmpty())
        {
            return response()->json(
                ['message' => 'Student is not registered on this section'],
                404
            );
        }

        $today = Carbon::now()->format('Y-m-d');

        try {
            foreach($students as $student)
            {
                DB::table('absences')->insert([
                    'student_id' => $student->student_id,
                    'section_id' => $student->section_id,
                    'absence_date' => $today,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $student->number_of_absence = $student->number_of_absence + 1 ;
                DB::table('student__sections')->where('student_id', $student->student_id)->where('section_id', $student->section_id)->update(['number_of_absence' => $student->number_of_absence]);
            }
        } catch (\Throwable $th) {
            return response(['message'=>$th], 400);
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
        $student->number_of_absence = $student->number_of_absence - 1 ;
        DB::table('student__sections')->where('student_id',$var['student_id'])->where('section_id',$var['section_id'])->update(['number_of_absence' => $student->number_of_absence]);

        return response(['massage' => 'Absence is deleted'], 200);
    }

    public function AbsenceHistory($section_id, $day)
    {
        // $all_students = Student_Section::where('section_id', $section_id)->get();
        $all_students = DB::table('student__sections')->join('students', 'student__sections.student_id', '=', 'students.student_id')->where('section_id', $section_id)->select('student__sections.student_id', 'student_name')->get();
        // $all_absence_students = Absence::where('section_id', $section_id)->where('absence_date', $day)->get();
        $all_absence_students = DB::table('absences')->where('section_id', $section_id)->where('absence_date', $day)->get();

        // return $all_students;

        if($all_students->isEmpty())
        {
            return response()->json(['massage' => 'No students in this section'], 400);
        }

        $students = [];
        $absence = false;

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

                    $students[] = $var;
                }
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
}
