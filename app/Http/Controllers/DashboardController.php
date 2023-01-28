<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student_Section;
use App\Models\Student;
use App\Models\Section;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\StudentCourse;
use App\Models\Absence;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function MostRegisteredCourses($number = 5)
    {
        $section_arr = DB::table('student__sections')->pluck('section_id');
       
        $temp = DB::table('student__sections')
                 ->join('sections', 'student__sections.section_id', '=', 'sections.section_id')
                 ->select('sections.course_id', DB::raw('count(*) as number'))
                 ->groupBy('sections.course_id')
                 ->orderByDesc('number')
                 ->orderBy('sections.course_id', 'asc')
                 ->take($number)
                 ->get();
        
        $result = $temp->groupBy('course_id')->map(function($courseData) {
                    return [
                        'course_id' => $courseData[0]->course_id,
                        'number' => $courseData->sum('number')
                    ];
                });
        
                return response()->json($result->values(),200);
    }

    public function MostAbsenceInSection($number = 5)
    {
        // $var = DB::table('absences')->select('section_id', DB::raw('count(*) as number'))->groupBy('section_id')->orderByDesc('number')->orderBy('section_id', 'asc')->simplePaginate($number);
        // $response = $var->toArray();
        // return response()->json($response['data'],200);
        $var = DB::table('absences')->select('section_id', DB::raw('count(*) as number'))->groupBy('section_id')->orderByDesc('number')->orderBy('section_id', 'asc')->take($number)->get();
   
        return response()->json($var,200);
    }

    public function MostInstructorTeaching($number = 5)
    {
        $var = DB::table('sections')->select('instructor_name', DB::raw('count(*) as number'))->groupBy('instructor_name')->orderByDesc('number')->orderBy('instructor_name', 'asc')->take($number)->get();
        return response()->json($var,200);
        
    }

    public function NumberOfAbsence($number = 5)
    {
        $absences = DB::table('absences')->select('absence_date', DB::raw('count(*) as number'))->groupBy('absence_date')->orderByDesc('number')->orderByDesc('absence_date')->take($number)->get();
        $absences_array = $absences->toArray();
        
        $response = [];
        
        foreach ($absences_array as $absence) 
        {
            $var = [
                'absence_date' => $absence->absence_date,
                'day' => Carbon::createFromFormat('Y-m-d', $absence->absence_date)->format('l'),
                'number' => $absence->number
            ];
            $response[] = $var;
        }

        return response()->json($response,200);
    }
}

