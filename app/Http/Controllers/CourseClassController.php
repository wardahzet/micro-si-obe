<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use Illuminate\Http\Request;

class CourseClassController extends Controller
{
    public function getClassesbyCourseName($courseName)
    {
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

    public function storeClass(Request $request)
    {
        //
    }
}
