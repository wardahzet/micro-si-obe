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

    public function getClassesbyCourseId($courseId)
    {
        //
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
}
