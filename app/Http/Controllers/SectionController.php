<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
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
        
        $request->validate([
            'section_id'=>'required',
            'course_id'=>'required',
            'instructor_id'=>'required',
            // 'instructor_name'=>'required',
            // 'classroom'=>'required',
            // 'time'=>'required'
         ]);
         return Section::create($request->all());
        
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
        return Section::Where('section_id',$id)->get();
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
        $section = Section::Where('section_id',$id)->update([
            'section_id'=>$request->section_id,
            'course_id'=>$request->course_id,
            'instructor_id'=>$request->instructor_id,
            // section name or instructor name is missing
            'classroom'=>$request->classroom,
            'time'=>$request->time
        ]);
        // $section->update(['section_id'=>$id]);
        return[$section];
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
         
        if($section->get()->isEmpty()){
            echo 'hi';
            return ['message'=>"section is not found"];
        }
        $section->delete();
    }

    public function search($name)
    {
        return Section::Where('section_name','like','%'.$name.'%')->get();
    }

   
}
