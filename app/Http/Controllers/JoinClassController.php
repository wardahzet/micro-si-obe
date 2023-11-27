<?php

namespace App\Http\Controllers;

use App\Models\CourseClass;
use App\Models\JoinClass;
use Illuminate\Http\Request;

class JoinClassController extends Controller
{
    public function index()
    {
        $joinClass = JoinClass::all();

        return response()->json([
            'success' => true,
            'data' => $joinClass
        ]);
    }

    public function show(Request $request)
    {
        $courseClassId = $request->course_class_id;
        $joinClasses = JoinClass::with(['courseClass'])
            ->where('course_class_id', $courseClassId)
            ->get();
        if ($joinClasses->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
        $classInfo = null;
        $studentsInfo = [];
        foreach ($joinClasses as $joinClass) {
            if ($classInfo === null) {
                $classInfo = [
                    'class' => $joinClass->course_class_id,
                    'class_code' => $joinClass->courseClass->class_code,
                    'class_name' => $joinClass->courseClass->name,
                    'course_id' => $joinClass->courseClass->course_id,
                ];
            }
            $studentsInfo[] = [
                'student_user_id' => $joinClass->student_user_id,
            ];
        }
        $classInfo['students'] = $studentsInfo;
        $result = [
            'success' => true,
            'data' => [
                'class' => $classInfo,
            ],
        ];

        return response()->json($result);
    }

    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,course_id',
            'student_user_id' => 'required',
        ]);


        $courseExists = CourseClass::where('course_id', $request->course_class_id)->exists();

        if (!$courseExists) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid course or student ID provided',
            ], 422); 
        }

  
        $joinClass = JoinClass::create([
            'course_class_id' => $request->course_class_id,
            'student_user_id' => $request->student_user_id,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diinputkan',
            'data' => [
                'joinClass' => $joinClass
            ]
        ]);
    }


    //fungsi delete member class
    public function deleteMemberClass($idClass, $idMember)
    {
        try {
            JoinClass::where('course_class_id', $idClass)->where('student_user_id', $idMember)->delete();

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
}
