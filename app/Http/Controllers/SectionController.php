<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

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
            // 'instructor_id' => 'required',
            // 'instructor_name'=>'required',
            // 'classroom'=>'required',
            // 'time'=>'required'
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
                'instructor_name' => $request->instructor_name,
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
                    'instructor_name' => $request->tutorial_instructor_name,
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
                    'instructor_name' => $request->lab_instructor_name,
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
        return Section::Where('section_id', $id)->get();
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
        // print_r($request->all()); 
        $section = Section::Where('section_id', $id)->update([
            'section_id' => $request->section_id,
            'course_id' => $request->course_id,
            'instructor_id' => $request->instructor_id,
            // section name or instructor name is missing
            'classroom' => $request->classroom,
            'time' => $request->time
        ]);
        // $section->update(['section_id'=>$id]);
        return [$section];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section =  Section::where('section_id', '=', $id);

        if ($section->get()->isEmpty()) {
            echo 'hi';
            return ['message' => "section is not found"];
        }
        $section->delete();
    }

    public function search($name)
    {
        return Section::Where('section_name', 'like', '%' . $name . '%')->get();
    }
}
