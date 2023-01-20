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
Route::get('/sections',[SectionController::class,'index']);
Route::post('/sections',[SectionController::class,'store']); //protected
Route::get('/sections/{id}',[SectionController::class,'show']);
Route::get('/sections/search/{name}',[SectionController::class,'search']);
Route::delete('/sections/{id}',[SectionController::class,'destroy']); //protected
Route::put('/sections/{id}',[SectionController::class,'update']); //protected
Route::get('/sections/instructor/{id}',[SectionController::class,'FindSectionInstrctor']);
Route::get('/sections/instructor/students/{instructor_id}/{student_id?}',[SectionController::class,'FindStudentForInstructor']);

// end of sections

// courses 
Route::get('/courses',[CourseController::class,'index']);
Route::post('/courses',[CourseController::class,'store']); //protected
Route::delete('/courses/{id}',[CourseController::class,'destroy']);
// end of courses

// students sections 
Route::get('/section/students/{id}',[StudentSectonController::class,'sectionStudentsList']);
Route::get('/students/sections/{id}',[StudentSectonController::class,'showStudentsSections']);
Route::post('/students/sections',[StudentSectonController::class,'store']);
Route::post('/students/sections/all',[StudentSectonController::class,'storeAll']);
Route::delete('students/sections',[StudentSectonController::class,'destroy']);
// Route::get('/instructor/sections/{id}',[StudentSectonController::class,'showInstructorSections']);
// Route::get('/sections/students/{id}',[StudentSectonController::class,'showStudents']);
// end of students sections 

// students
// Route::get('/students/{id}',[StudentController::class,'show']);
Route::post('/students',[StudentController::class,'show']);
Route::get('/students/{student_id}/{instructor_id?}',[StudentController::class,'showGet']);
Route::put('/students/{id}',[StudentController::class,'update']);
Route::get('/students',[StudentController::class,'index']);
Route::delete('/students/{id}',[StudentController::class,'destroy']);
// end of students 


// instructor
Route::put('/instructor/{id}',[InstructorController::class,'update']);
Route::get('/instructor/sections/{id}',[InstructorController::class,'showSections']);
Route::delete('/instructor/{id}',[InstructorController::class,'destroy']);
// end of instructor

// admin
// Route::put('/admin/{id}',[AdminController::class,'update']);
// end admin

// authentication
    //student
    Route::post('/students/register',[StudentAuthController::class, 'register']);
    Route::post('/students/login',[StudentAuthController::class, 'login']);
    Route::post('/students/logout',[StudentAuthController::class, 'logout']); // protected 

    //admin
    Route::post('/admin/login',[AdminAuthController::class, 'login']);
    Route::post('/admin/logout',[AdminAuthController::class, 'logout']); // protected 

    //instructor
    Route::get('/instructors',[InstructorAuthController::class, 'index']);
    Route::post('/instructor/register',[InstructorAuthController::class, 'register']);
    Route::post('/instructor/login',[InstructorAuthController::class, 'login']);
    Route::post('/instructor/logout',[InstructorAuthController::class, 'logout']); // protected 
// end of authentication

// absence 
Route::get('student/absence/{id}',[AbsenceController::class,'show']);
Route::post('student/absence',[AbsenceController::class,'store']);
Route::delete('student/absence',[AbsenceController::class,'destroy']);



/* protected */

Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/sections',[SectionController::class,'index']);
});
