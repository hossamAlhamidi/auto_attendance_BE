<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;


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

            // 'has_tutorial' => 'int|nullable',
            // 'has_lab' => 'int|nullable'

            // 'classroom'=>'int|nuallable',
            // 'time'=>'required'
         ]);

         $course = Course::create([
            'course_id' => $var['abbreviation'] . ' ' . $var['course_id'],
            'course_name' => $var['course_name'],
            'course_hours' => $var['course_hours']
         ]);
         
         if(!$course)
            return response('Error, coudle not add the course', 400);
         else
            return response('The course was add', 201);
         

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
        $course = DB::table('courses')->where('course_id', $id);
        if(!$course)
        {
            return response('Course with this ID not found', 404);
        }
        $course->delete();
        return response('Courses is deleted', 200);
    }
}
