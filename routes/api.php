<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentSectonController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\InstructorAuthController;

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
// end of sections

// courses 
Route::get('/courses',[CourseController::class,'index']);
Route::post('/courses',[CourseController::class,'store']); //protected
// end of courses

// students sections 
Route::get('/section/students/{id}',[StudentSectonController::class,'sectionStudentsList']);
Route::get('/section/students/{id}',[StudentSectonController::class,'showStudents']);
Route::get('/students/sections/{id}',[StudentSectonController::class,'showSections']);
// end of students sections 

// students
// Route::get('/students/{id}',[StudentController::class,'show']);
Route::post('/students',[StudentController::class,'show']);
Route::get('/students/{id}',[StudentController::class,'showGet']);
Route::put('/students/{id}',[StudentController::class,'update']);
Route::get('/students',[StudentController::class,'index']);
// end of students 

// authentication
    //student
    Route::post('/students/register',[StudentAuthController::class, 'register']);
    Route::post('/students/login',[StudentAuthController::class, 'login']);
    Route::post('/students/logout',[StudentAuthController::class, 'logout']); // protected 

    //admin
    Route::post('/admin/login',[AdminAuthController::class, 'login']);
    Route::post('/admin/logout',[AdminAuthController::class, 'logout']); // protected 

    //instructor
    Route::post('/instructor/register',[InstructorAuthController::class, 'register']);
    Route::post('/instructor/login',[InstructorAuthController::class, 'login']);
    Route::post('/instructor/logout',[InstructorAuthController::class, 'logout']); // protected 
// end of authentication



/* protected */
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// Route::group(['middleware' => ['auth:sanctum']], function(){
// });
