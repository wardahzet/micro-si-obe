<?php

namespace App\Http\Controllers;

use App\Models\JoinClass;
use Illuminate\Http\Request;

class JoinClassController extends Controller
{
    public function index()
    {
        $joinClass = JoinClass::all();

        return response()->json([
            'success'=> true,
            'data'=>$joinClass
        ]);
    }

    public function show(Request $request)
    {
        $courseClassId = $request->course_class_id;

        // Menghitung jumlah mahasiswa dengan course_class_id yang sama
        $studentCount = JoinClass::where('course_class_id', $courseClassId)->count();

        // Mengambil data JoinClass dengan relasi CourseClass
        $joinClass = JoinClass::with(['courseClass'])
            ->where('course_class_id', $courseClassId)
            ->first();

        if (!$joinClass) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'class' => $joinClass->course_class_id,
                'class_code' => $joinClass->courseClass->class_code,
                'class_name' => $joinClass->courseClass->name,
                'course_id' => $joinClass->courseClass->course_id,
                'student_count' => $studentCount,
            ],
        ]);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required',
            'student_user_id' => 'required',
        ]);

        $joinClass = JoinClass::create([
            "course_class_id" => $request->course_class_id,
            "student_user_id" => $request->student_user_id,
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
