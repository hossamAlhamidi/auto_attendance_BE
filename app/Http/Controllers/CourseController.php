<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Course::All();
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
            'course_id' => 'required|string',
            'course_name' => 'required|string',
            'abbreviation' => 'required|string',
            'course_hours' => 'required|int',
            'has_tutorial' => '',
            'has_lab' => ''

            // 'classroom'=>'int|nuallable',
            // 'time'=>'required'
        ]);

        $course_id = $var['abbreviation'] . ' ' . $var['course_id'];

        if(Course::where('course_id', $course_id)->first())
        {
            return response()->json(
                ['message' => 'Course already exit']
                ,400);
        }

        $course = Course::create([
            'course_id' => $course_id,
            'course_name' => $var['course_name'],
            'course_hours' => $var['course_hours'],
            'has_tutorial'=>$var['has_tutorial'],
            'has_lab'=>$var['has_lab']
        ]);
         
        if(!$course)
            return response(['message' => 'Error, coudle not add the course'], 400);
        else
            return response(['message' => 'The course was add'], 201);
         

    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Course::where('course_id', $id)->first();
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
        // if(!auth()->guard('instructor')->user()->Auth::guard('instructor'))
        // {
        //     return response()->json(['massage' => 'unauthorized'], 403);
        // }

        $course = DB::table('courses')->where('course_id', $id);
        
        if(!$course->first())
        {
            return response(['message' => 'Course with this ID not found'], 404);
        }
        $course->delete();
        return response(['message' => 'Courses is deleted'], 200);
    }
}