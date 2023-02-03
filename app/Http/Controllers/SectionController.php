<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\StudentSectonController;
use Dotenv\Repository\RepositoryInterface;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Section::All();
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
            'section_id' => 'required',
            'course_id' => 'required',
            'instructor_id' => 'required',
            'instructor_name'=>'required',
            'classroom'=>'required',
            'time'=>'required'
        ]);

        $section = Section::where('section_id', $var['section_id'])->first();
        if ($section) :
            return response(
                ['message' => 'Duplicate section id'],
                401
            );
        endif;

        $decoded_time = json_decode($request->time);

        $sunday = isset($decoded_time->sunday) ? $decoded_time->sunday : "-";
        $monday = isset($decoded_time->monday) ? $decoded_time->monday : "-";
        $tuesday = isset($decoded_time->tuesday) ? $decoded_time->tuesday : "-";
        $wednesday = isset($decoded_time->wednesday) ? $decoded_time->wednesday : "-";
        $thursday = isset($decoded_time->thursday) ? $decoded_time->thursday : "-";

        $insert_lecture =
            [
                'section_id' => $request->section_id,
                'course_id' => $request->course_id,
                'course_name' => $request->course_name,
                'instructor_id' => $request->instructor_id,
                'instructor_name' => $request->instructor_name?$request->instructor_name:"Not chosen yet",
                'classroom' => $request->classroom,
                'time' => [
                    "sunday" => $sunday,
                    "monday" => $monday,
                    "tuesday" => $tuesday,
                    "wednesday " => $wednesday,
                    'thursday' => $thursday
                ],
                'type' => 'lecture',
            ];

        // end lecture 


        // tut check
        if (isset($request->tutorial_section_id)) {
            $tutorial = Section::where('section_id', $var['section_id'] + 1)->first();
            if ($tutorial) :
                return response(
                    ['message' => 'Section tutorial ID is taken , please remove that section first'],
                    401
                );
            endif;

            $tutorial_decoded_time = json_decode($request->tutorial_time);

            $tut_sunday = isset($tutorial_decoded_time->sunday) ? $tutorial_decoded_time->sunday : "-";
            $tut_monday = isset($tutorial_decoded_time->monday) ? $tutorial_decoded_time->monday : "-";
            $tut_tuesday = isset($tutorial_decoded_time->tuesday) ? $tutorial_decoded_time->tuesday : "-";
            $tut_wednesday = isset($tutorial_decoded_time->wednesday) ? $tutorial_decoded_time->wednesday : "-";
            $tut_thursday = isset($tutorial_decoded_time->thursday) ? $tutorial_decoded_time->thursday : "-";

            $insert_tutorial =
                [
                    'section_id' => $request->tutorial_section_id,
                    'course_id' => $request->course_id,
                    'course_name' => $request->course_name,
                    'instructor_id' => $request->tutorial_instructor_id,
                    'instructor_name' => $request->tutorial_instructor_name?$request->tutorial_instructor_name:"Not chosen yet",
                    'classroom' => $request->tutorial_classroom,
                    'time' => [
                        "sunday" => $tut_sunday,
                        "monday" => $tut_monday,
                        "tuesday" => $tut_tuesday,
                        "wednesday " => $tut_wednesday,
                        'thursday' => $tut_thursday
                    ],
                    'type' => 'tutorial',
                ];

            Section::create($insert_tutorial);
        }
        //lab check
        if (isset($request->lab_section_id)) {
            $lab = Section::where('section_id', $var['section_id'] + 2)->first();
            if ($lab) :
                return response(
                    ['message' => 'Section Lab ID is taken , please remove that section first'],
                    401
                );
            endif;

            $lab_decoded_time = json_decode($request->lab_time);

            $lab_sunday = isset($lab_decoded_time->sunday) ? $lab_decoded_time->sunday : "-";
            $lab_monday = isset($lab_decoded_time->monday) ? $lab_decoded_time->monday : "-";
            $lab_tuesday = isset($lab_decoded_time->tuesday) ? $lab_decoded_time->tuesday : "-";
            $lab_wednesday = isset($lab_decoded_time->wednesday) ? $lab_decoded_time->wednesday : "-";
            $lab_thursday = isset($lab_decoded_time->thursday) ? $lab_decoded_time->thursday : "-";

            $insert_lab =
                [
                    'section_id' => $request->lab_section_id,
                    'course_id' => $request->course_id,
                    'course_name' => $request->course_name,
                    'instructor_id' => $request->lab_instructor_id,
                    'instructor_name' => $request->lab_instructor_name?$request->lab_instructor_name:"Not chosen yet",
                    'classroom' => $request->lab_classroom,
                    'time' => [
                        "sunday" => $lab_sunday,
                        "monday" => $lab_monday,
                        "tuesday" => $lab_tuesday,
                        "wednesday " => $lab_wednesday,
                        'thursday' => $lab_thursday
                    ],
                    'type' => 'lab',
                ];

            Section::create($insert_lab);
        }

        return Section::create($insert_lecture);;
        // return DB::table("sections")->insert($insert_sections);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return Section::Exttransfer()->where('section_id',$id)->get();
        return Section::Where('section_id', $id)->first();
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
       

        $data = $request->all();

         $request->validate([
            'section_id'=>'required',
            'instructor_id' => 'required',
            'instructor_name' => 'required|string',
            'classroom' => 'required|string',
            'time' => 'required',
        ]);
        if (is_string($request->time) && json_decode($request->time) != null) {
            $decoded_time = json_decode($request->time);
            $sunday = isset($decoded_time->sunday) ? $decoded_time->sunday : "-";
            $monday = isset($decoded_time->monday) ? $decoded_time->monday : "-";
            $tuesday = isset($decoded_time->tuesday) ? $decoded_time->tuesday : "-";
            $wednesday = isset($decoded_time->wednesday) ? $decoded_time->wednesday : "-";
            $thursday = isset($decoded_time->thursday) ? $decoded_time->thursday : "-";
        
            $validatedData = [
                'instructor_id' => $request->instructor_id,
                'instructor_name' => $request->instructor_name,
                'classroom' => $request->classroom,
                'time' => [
                    "sunday" => $sunday,
                    "monday" => $monday,
                    "tuesday" => $tuesday,
                    "wednesday" => $wednesday,
                    'thursday' => $thursday
                ],
            ];
        } else {
            $validatedData = [
                'instructor_id' => $request->instructor_id,
                'instructor_name' => $request->instructor_name,
                'classroom' => $request->classroom,
                'time' => $request->time,
            ];
        }
        $update = DB::table('sections')->where('section_id', $data['section_id'])->update($validatedData);
    
        if($update) {
            return response(['message' => 'Record updated successfully']);
        } else {
            return response(['message' => 'Error updating record']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $section =  Section::where('section_id', $id);
        $section = DB::table('sections')->join('courses', 'sections.course_id', '=', 'courses.course_id')
            ->where('section_id', $id);

        if (!$section->first()) 
        {
            return response()->json(['message' => "Section is not found"], 404);
        }

        $section_data = $section->first();

        if($section_data->type != 'lecture'){
            $section->delete();
            return response()->json(['message' => 'Section is deleted'], 200);
        }

        if($section_data->has_tutorial == 1)
        {  
            $tutorial = Section::where('section_id', $section_data->section_id+1);
        }
        if($section_data->has_lab == 1)
        {  
            $lab = Section::where('section_id', $section_data->section_id+2);
        }

        if($tutorial->first())
        {
            // return response()->json(['message' => "Tutorial for this Lucture can't be found"], 404);
            $tutorial->delete();
        }
        if($lab->first())
        {
            // return response()->json(['message' => "Lab for this Lucture can't be found"], 404);
            $lab->delete();
        }

        // $tutorial->delete();
        // $lab->delete();
        $section->delete();

        return response(
            ['message' => 'sections are deleted']
            , 200);
    }

    public function search($name)
    {
        return Section::Where('section_name', 'like', '%' . $name . '%')->get();
    }

    public function FindSectionInstrctor($instructor_id)
    {
        $sections = Section::where('instructor_id', $instructor_id)->get();

        if(!$sections->first())
        {
            return response(['message' => 'There is no section(s) assigned to this ID'], 404);
        }

        $response = [];

        foreach($sections as $section)
        {
            $var = [
                'section_id' => $section['section_id'],
                'course_name' => $section['course_name'],
                'course_id' => $section['course_id'],
                'type' => $section['type'],
                'classroom' => $section['classroom'],
                'time' => $section['time'],
            ];
            $response[] = $var;
        }
        return response($response, 200);
    }

    public function FindStudentForInstructor($instructor_id, $student_id = 0)
    {
        $sections = Section::where('instructor_id', $instructor_id)->get('section_id');

        if(!$sections->first())
        {
            return response(['message' => 'There is no section(s) assigned to this ID'], 404);
        }

        $students = [];

        foreach ($sections as $section) 
        {
            $student = (new StudentSectonController)->sectionStudentsList($section['section_id']);
            
            $students[] = $student;
        }

        $response = [];

        foreach($students as $students_in_section)
        {
            foreach($students_in_section as $student)
            {
                $var = [
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'email' => $student->email,
                    'section_id' => $student->section_id,
                    'absence_percentage' => $student->absence_percentage,
                    'number_of_absence' => $student->number_of_absence,

                ];
                $response[] = $var;
            }
        }

        if($student_id != 0)
        {
            $response_one_student = [];
            foreach ($response as $student) {
                if($student['student_id'] == $student_id)
                {
                    $var = [
                        'student_id' => $student['student_id'],
                        'student_name' => $student['student_name'],
                        'email' => $student['email'],
                        'section_id' => $student['section_id'],
                        'absence_percentage' => $student['absence_percentage'],
                        'number_of_absence' => $student['number_of_absence'],
                    ];
                    $response_one_student[] = $var;
                }
            }
            // return response($response_one_student, 200); 
            return $response_one_student; 
        }
        // return response($response, 200); 
        return $response; 
    }
}
