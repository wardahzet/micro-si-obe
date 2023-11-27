<?php

use App\Http\Controllers\CourseClassController;
use App\Http\Controllers\JoinClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/classes', [CourseClassController::class, 'getAllClass']); //v
Route::post('/classes', [CourseClassController::class, 'create']);//v
Route::delete('/class/{classCode}', [CourseClassController::class, 'deleteClass']);//v
Route::get('/classes/search-id/{courseId}', [CourseClassController::class, 'getClassesByCourseId'])->name('courses.searchByCourseId'); //v
Route::put('/classes/{id}', [CourseClassController::class, 'editCourseClass']);//V
Route::get('/classes/search-name/{name}', [CourseClassController::class, 'getClassesBySearchName']); //v
Route::delete('/class/{idClass}/student/{id}', [JoinClassController::class, 'deleteMemberClass']);
Route::post('/joinClass', [JoinClassController::class,'store']);//v
Route::get('/joinClass', [JoinClassController::class,'index']);//v
Route::get('/joinClass/{course_class_id}', [JoinClassController::class,'show']); //v

