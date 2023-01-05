<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absence;
use App\Models\Course;
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
 
        
        $now = now();
        try{
            $absence= DB::insert("insert into absences (student_id, section_id,created_at,updated_at) values ($var[student_id], $var[section_id],'$now','$now')");

        }
        catch (\Throwable $th) {
            return response(['message'=>'Error, could not add the absence'], 400);
        }

        return response('absence is added', 201);
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
            return response('this Absence info is not found', 404);
        }
        $absence->delete();
        return response('Absence is deleted', 200);
    }
}
