<?php

namespace App\Http\Controllers;
use App\Models\Instructor;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student_Section;


class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Instructor::all();
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
    public function show($id)
    {
        //
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

        $instructor = Instructor::Where('instructor_id', $validated_id)->first();

        if(!$instructor){
            return response([
                'message'=>'No Instructor found by This ID'
            ],401);
        }

        $instructor->update($request->all());

        $response = [
            'instructor_id' => $instructor['instructor_id'],
            'instructor_name' => $instructor['instructor_name'],
            'email' => $instructor['email'],
            'phone_number' => $instructor['phone_number']
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
        Instructor::where('instrcutor_id', $id)->delete();

    }


    public function showSections($instructor_id)
    {
        $result = DB::select( DB::raw("SELECT section_id, `course_id`, `instructor_name`, `classroom`, `time` FROM sections where instructor_id = $instructor_id ") );
        return $result;
    }
}
