<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student_Section;
use App\Models\Student;
use App\Models\Section;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student_Course;
use App\Models\Absence;

class DashboardController extends Controller
{
    public function MostRegisteredCourses(Request $request)
    {
        # code...
    }

    public function MostAbsenceInSection(Request $request)
    {
        # code...
    }

    public function MostInstructorTeaching(Request $request)
    {
        # code...
    }

    public function NumberOfAbsence(Request $request)
    {
        # code...
    }
}

