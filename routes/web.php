<?php

use App\Http\Controllers\CourseClassController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JoinClassController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/courses/search-name/{courseName}', [CourseClassController::class, 'getClassesByCourseName'])->name('courses.search');
Route::get('/courses/search-id/{courseId}', [CourseClassController::class, 'getClassesByCourseId'])->name('courses.searchByCourseId');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return ('welcome');
});

