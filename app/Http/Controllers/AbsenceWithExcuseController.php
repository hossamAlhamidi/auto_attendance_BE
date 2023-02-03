<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absence;
use App\Models\Course;
use App\Models\Section;
use App\Models\Student_Section;

class AbsenceWithExcuseController extends Controller
{
    public function store(Request $request)
    {
        $var = $request->validate([
            'student_id'=>'required|max:9',
            'section_id'=>'required|max:7',
            'absence_date'=>'required|date',
            'reason'=>'max:150',
            'file'=>'max:150'
             
        ]);
        
       

        $student = DB::table('student__sections')->where('student_id',$var['student_id'])->where('section_id',$var['section_id'])->first();
    //    $student = DB::select(DB::raw("SELECT * FROM `student__sections` where section_id = $var[section_id] and student_id = $var[student_id] "));
        if(!$student){
            return response(
                ['message' => 'Student is not registered on this section'],
                404
            );
        }
       
        try{
            // $absence= DB::insert("insert into absences (student_id, section_id, absence_date,created_at,updated_at) values ($var[student_id], $var[section_id],'$today','$now','$now')");
            // DB::insert("insert into absences (student_id, section_id, absence_date, created_at, updated_at) values ($var[student_id], $var[section_id],'$today','$now','$now')");
            DB::table('absence_with_excuses')->insert([
                'student_id' => $student->student_id,
                'section_id' => $student->section_id,
                'absence_date' => $var['absence_date'],
                'reason' => $var['reason'],
                'file' => $var['file'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        catch (\Throwable $th) {
            return response(['message'=>$th], 400);
        }

        return response(['massage' => 'excuse is added'], 201);
    }


    public function show($student_id)
    {
        
        $result = DB::select(DB::raw("SELECT * FROM `absence_with_excuses`,`sections` where sections.section_id = absence_with_excuses.section_id and student_id = $student_id "));
        return $result;
    }

}
