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

Route::get('/classes', [CourseClassController::class, 'getAllClassApi']);

Route::get('/classes/search-id/{courseId}', [CourseClassController::class, 'getClassesByCourseId']); //v
Route::get('/classes/search-name/{name}', [CourseClassController::class, 'getClassesBySearchName']); //v
Route::post('/classes', [CourseClassController::class, 'createAPI']);//v
Route::put('/classes/{id}', [CourseClassController::class, 'updateAPI']);//V
Route::delete('/classes/{classCode}', [CourseClassController::class, 'deleteAPI']);//v

Route::get('/joinClass', [JoinClassController::class,'index']);//v
Route::get('/joinClass/{course_class_id}', [JoinClassController::class,'show']); //v
Route::post('/joinClass', [JoinClassController::class,'storeAPI']);//v
Route::delete('/class/{idClass}/student/{id}', [JoinClassController::class, 'deleteAPI']);

