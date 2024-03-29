<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentSectonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\InstructorAuthController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AbsenceWithExcuseController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* public */
// sections
// Route::get('/sections',[SectionController::class,'index']);

Route::get('/sections/{id}', [SectionController::class, 'show']);
Route::get('/sections/search/{name}', [SectionController::class, 'search']);

Route::get('/sections/instructor/{id}', [SectionController::class, 'FindSectionInstrctor']);
Route::get('/sections/instructor/students/{instructor_id}/{student_id?}', [SectionController::class, 'FindStudentForInstructor']);

// end of sections



// students sections
Route::get('/section/students/{id}', [StudentSectonController::class, 'sectionStudentsList']);
Route::get('/students/sections/{id}', [StudentSectonController::class, 'showStudentsSections']);


// Route::get('/instructor/sections/{id}',[StudentSectonController::class,'showInstructorSections']);
// Route::get('/sections/students/{id}',[StudentSectonController::class,'showStudents']);
// end of students sections

// students
// Route::get('/students/{id}',[StudentController::class,'show']);
Route::post('/students', [StudentController::class, 'show']);
Route::get('/students/{student_id}/{instructor_id?}', [StudentController::class, 'showGet']);
Route::get('/students', [StudentController::class, 'index']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);
Route::put('/students/mobile/updatePassword/{id}', [StudentController::class, 'updatePassword']);
// Route::put('/students',[StudentController::class,'update']);
// end of students


// instructor
// Route::put('/instructor/{id}',[InstructorController::class,'update']);
Route::get('/instructor/sections/{id}', [InstructorController::class, 'showSections']);
Route::put('/instructor/mobile/updatePassword/{id}', [InstructorController::class, 'updatePassword']);
// Route::get('/instructor/{id}',[InstructorController::class, 'show']);

// Route::post('/instructor/logout',[InstructorAuthController::class, 'logout']); // protected
// end of instructor



// authentication
//student
Route::post('/students/register', [StudentAuthController::class, 'register']);
Route::post('/students/login', [StudentAuthController::class, 'login']);
Route::post('/students/logout', [StudentAuthController::class, 'logout']); // protected
Route::put('/students/forgetPassword/{id}', [StudentAuthController::class, 'forgetPassword']);

//admin
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout']); // protected

//instructor
Route::post('/instructor/register', [InstructorAuthController::class, 'register']);
Route::post('/instructor/login', [InstructorAuthController::class, 'login']);
Route::put('/instructor/forgetPassword/{id}',[InstructorAuthController::class, 'forgetPassword']);

// end of authentication

// absence
Route::get('student/absence/{id}', [AbsenceController::class, 'show']);
Route::get('student/excuse/{id}', [AbsenceWithExcuseController::class, 'show']);
Route::post('student/absence', [AbsenceController::class, 'store']);
Route::post('student/excuse', [AbsenceWithExcuseController::class, 'store']);
Route::delete('student/absence', [AbsenceController::class, 'destroy']);
Route::delete('student/absence/multi', [AbsenceController::class, 'multiDelete']);
Route::get('student/absence/history/{id}/{day}', [AbsenceController::class, 'AbsenceHistory']);
Route::post('student/absence/multi', [AbsenceController::class, 'multiAbsence']);
// end of absence



/* protected */

Route::group(['middleware' => ['auth:sanctum']], function () {
    // instructor
    Route::get('/instructor', [InstructorController::class, 'show']);
    Route::post('/instructor/logout', [InstructorAuthController::class, 'logout']);
    Route::put('/instructor', [InstructorController::class, 'update']);
    Route::delete('/instructor/{id}', [InstructorController::class, 'destroy']);
    Route::get('/instructors', [InstructorController::class, 'index']);
    // Route::delete('/instructor/{id}',[InstructorController::class,'destroy']);
    // end instructor

    // students
    Route::put('/students', [StudentController::class, 'update']);
    // end students



    // section
    Route::get('/sections', [SectionController::class, 'index']);
    // end sections

    // dashboard
    Route::get('dashboard/courses/{number?}', [DashboardController::class, 'MostRegisteredCourses']);
    Route::get('dashboard/section/{number?}', [DashboardController::class, 'MostAbsenceInSection']);
    Route::get('dashboard/instructor/{number?}', [DashboardController::class, 'MostInstructorTeaching']);
    Route::get('dashboard/absence/{from?}/{to?}', [DashboardController::class, 'NumberOfAbsence']);
    Route::get('dashboard/count', [DashboardController::class, 'count']);
    //end of dashboard

    // student sections
    Route::post('/students/sections', [StudentSectonController::class, 'store']);
    Route::post('/students/sections/all', [StudentSectonController::class, 'storeAll']);
    Route::delete('/students/sections', [StudentSectonController::class, 'destroy']);
    // end of student sections

    // courses
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']); //protected
    Route::put('/courses', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    // end of courses

    // sections
    Route::post('/sections', [SectionController::class, 'store']); //protected
    Route::delete('/sections/{id}', [SectionController::class, 'destroy']); //protected
    Route::put('/sections', [SectionController::class, 'update']); //protected
    // end of sections

});
