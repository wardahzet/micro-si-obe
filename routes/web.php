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
Route::get('/getAll', [CourseClassController::class, 'getAllClassUI'])->name('getAllClass');

Route::get('/classes', [CourseClassController::class, 'showCreateForm'])->name('class.create.form');
Route::post('/classes', [CourseClassController::class, 'createUI'])->name('class.create');
Route::delete('/class/{classCode}', [CourseClassController::class, 'deleteUI'])->name('deleteclass');
Route::delete('/class/{idClass}/student/{id}', [JoinClassController::class, 'deleteUI'])->name('deleteStudent');
Route::post('/joinClass', [JoinClassController::class,'storeUI'])->name('addStudent');
Route::put('/classes/{id}', [CourseClassController::class, 'updateUI'])->name('course_classes.update');
Route::get('/classes/search', [CourseClassController::class, 'search'])->name('course_classes.search');
Route::get('/classes/{id}/edit', [CourseClassController::class, 'edit'])->name('course_classes.edit');
