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


Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return ('welcome');
});


// Route::get('/classes', [CourseClassController::class, 'showCreateForm'])->name('class.create.form');
// Route::post('/classes', [CourseClassController::class, 'create'])->name('class.create');
// Route::delete('/class/{classCode}', [CourseClassController::class, 'deleteClass'])->name('deleteclass');
// Route::delete('/class/{idClass}/student/{id}', [JoinClassController::class, 'deleteMemberClass'])->name('deleteStudent');
// Route::post('/joinClass', [JoinClassController::class,'store'])->name('addStudent');
// Route::get('/joinClass', [JoinClassController::class,'index']);
// Route::get('/joinClass/{course_class_id}', [JoinClassController::class,'show']);
// Route::put('/classes/{id}', [CourseClassController::class, 'update'])->name('course_classes.update');
// Route::get('/classes/search-id/{courseId}', [CourseClassController::class, 'getClassesByCourseId'])->name('courses.searchByCourseId');
// Route::get('/classes/search-name/{name}', [CourseClassController::class, 'getClassesBySearchName']);
// Route::get('/getAll', [CourseClassController::class, 'getAllClass'])->name('getAllClass');
// Route::get('/classes/search', [CourseClassController::class, 'search'])->name('course_classes.search');
// Route::get('/classes/{id}/edit', [CourseClassController::class, 'edit'])->name('course_classes.edit');

