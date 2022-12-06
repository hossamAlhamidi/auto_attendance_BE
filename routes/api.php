<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentSectonController;
use App\Http\Controllers\StudentController;
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

//sections
Route::get('/sections',[SectionController::class,'index']);
Route::post('/sections',[SectionController::class,'store']);
Route::get('/sections/{id}',[SectionController::class,'show']);
Route::get('/sections/search/{name}',[SectionController::class,'search']);
Route::delete('/sections/{id}',[SectionController::class,'destroy']);
Route::put('/sections/{id}',[SectionController::class,'update']);
// end of sections

//courses 
Route::get('/courses',[CourseController::class,'index']);
Route::post('/courses',[CourseController::class,'store']);
//end of courses
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// students sections 
Route::get('/section/students/{id}',[StudentSectonController::class,'sectionStudentsList']);
//end

// students
Route::get('/students/{id}',[StudentController::class,'show']);