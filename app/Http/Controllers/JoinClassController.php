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
                $response = $client->request('GET', "http://127.0.0.1:1000/api/users/{$studentUserId}");
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

        return response()->json($result);
    }


    public function store(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,course_id',
            'student_user_id' => 'required',
        ]);
        $courseExists = CourseClass::where('course_id', $request->course_class_id)->exists();
        if (!$courseExists) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid course ID provided',
            ], 422);
        }
        $client = new Client();
        try {
            $response = $client->request('GET', "http://127.0.0.1:1000/api/users/{$request->student_user_id}");
            $userData = json_decode($response->getBody(), true);
            if ($userData['role'] !== 'student') {
                return response()->json([
                    'success' => false,
                    'message' => 'User role other than student is not allowed to join class',
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
                    'joinClass' => $joinClass,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user data from external API',
            ], 500);
        }
    }

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
