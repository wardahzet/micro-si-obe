<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\JoinClass;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $client = new Client();

        $userData = [];
        foreach ($studentsInfo as $student) {
            $studentUserId = $student['student_user_id'];

            try {
                $response = $client->request('GET', "http://127.0.0.1:6000/api/users/{$studentUserId}");
                $userData[] = json_decode($response->getBody(), true);
            } catch (\Exception $e) {
                $userData[] = ['error' => 'Failed to fetch user'];
            }
        }

        $classInfo['students'] = $userData;

        $result = [
            'success' => true,
            'data' => [
                'class' => $classInfo,
            ],
        ];

        return $result;
    }


    public function store(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
            'student_user_id' => 'required',
        ]);

        $data = JoinClass::where('course_class_id', $request->course_class_id)->where('student_user_id',$request->student_user_id)->get();
        if(count($data) == 0) 
            JoinClass::create([
                'course_class_id' => $request->course_class_id,
                'student_user_id' => $request->student_user_id,
            ]);

        return redirect()->back();
    }

    public function deleteMemberClass($idClass, $idMember)
    {
        try {
            JoinClass::where('course_class_id', $idClass)->where('student_user_id', $idMember)->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function getUsers()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'http://127.0.0.1:1000/api/users');
            $users = json_decode($response->getBody(), true);
            return response()->json($users, $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch users'], 500);
        }
    }
}
