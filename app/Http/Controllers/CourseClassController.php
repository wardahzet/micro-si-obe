<?php

namespace App\Http\Controllers;

use App\Models\JoinClass;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseClassController extends Controller
{
    //fungsi delete class
    public function deleteClass($id)
    {
        try {
            DB::transaction(function () use ($id){
                $courseClass = CourseClass::find($id);
                
                JoinClass::destroy(collect($courseClass->join_classes)->pluck('id'));
                $courseClass->delete();
            });
            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create(Request $request)
    {
       
            
            $CourseClass = CourseClass::create([
                'course_id'=> $request->course_id,
                'name'=> $request->name,
               // 'thumbnail_img'=> $request->thumbnail_img,
                'class_code'=> $request->class_code,
                'creator_user_id'=> $request->creator_user_id,
                'syllabus_id'=> $request-> syllabus_id,
               // 'settings'=> $request->settings,
            ]);
    
            return response()->json([
                'status' => 'Success',
                'message' => 'new class created',
                'data' => [
                    'class' => $CourseClass,
                ]
                ], 200);
    }


    public function getAllClass()
    {
        $CourseClass = CourseClass::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'All class grabbed',
            'data'=>[
                'classes' => $CourseClass,
            ]
            ],200);
    }

    public function getClassesbyCourseName($courseName)
    {
        //
        try {
            $courses = CourseClass::where('name', 'LIKE', "%$courseName%")->get();
            if ($courses->isEmpty()) {
                return response()->json(['error' => 'Course tidak ditemukan'], 404);
            }
            $responseData = [
                'course_classes' => $courses->toArray()
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getClassesbyCourseId($courseId)
    {
        //
        try {
            $courses = CourseClass::where('course_id', $courseId)->get();
            if ($courses->isEmpty()) {
                return response()->json(['error' => 'Course tidak ditemukan'], 404);
            }
            $responseData = [
                'course_classes' => $courses->toArray()
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
