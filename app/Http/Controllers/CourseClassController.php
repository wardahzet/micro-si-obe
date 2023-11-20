<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    public function getClassesbyCourseName($courseName)
    {
        //
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
