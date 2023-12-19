<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\JoinClass;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CourseClassController extends Controller
{
    private function getAllClass()
    {
        $classes = CourseClass::all();
        $result = [];
        foreach ($classes as $class) {
            $syllabus = Http::get('http://localhost:5000/syllabi/' . $class->syllabus_id)->json();
            $course = Http::get('http://localhost:5000/courses/' . $class->course_id)->json();
            $result[] = [
                "id" => $class->id,
                'name' => $class->name,
                'thumbnail_img' => $class->thumbnail_img,
                'class_code' => $class->class_code,
                'creator_user_id' => $class->creator_user_id,
                'settings' => $class->settings,
                'course' => $course,
                'syllabus' => $syllabus,
            ];
        }
        return $result;
    }

    public function getAllClassApi() 
    {
        $classes = $this->getAllClass();
        if (count($classes) == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'kelas tidak ditemukan',
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'All class grabbed',
            'data' => [
                'classes' => $classes,
            ]
        ], 200);
    }

    public function getAllClassUI()
    {        
        $result = $this->getAllClass();
        return view('getAll', compact('result'));
    }

    public function showCreateForm()
    {
        $courses = Http::get('http://localhost:5000/courses')->json()['syllabi'];
        $syllabi = Http::get('http://localhost:5000/syllabi')->json()['syllabi'];

        return view('create', compact('courses', 'syllabi'));
    }

    private function create(Request $request)
    {
        $syllabi = Http::get('http://localhost:5000/syllabi/'.$request->syllabus_id)->json();
        $course = Http::get('http://localhost:5000/courses/'.$request->course_id)->json();
        if (CourseClass::where('class_code', $request->class_code)->first() != null) 
            throw new \Exception ('class_code sudah ada');
        if ($course != null && $syllabi != null) {
            $CourseClass = CourseClass::create([
                'course_id' => $request->course_id,
                'name' => $request->name,
                // 'thumbnail_img'=> $request->thumbnail_img,
                'class_code' => $request->class_code,
                'creator_user_id' => $request->creator_user_id,
                'syllabus_id' => $request->syllabus_id,
                // 'settings'=> $request->settings,
            ]);
            return $CourseClass;
        }
        else throw new \Exception ("kelas\/syllabi tidak ditemukan");
    }

    public function createUI(Request $request)
    {
        $this->create($request);
        return redirect()->route('getAllClass');
    }

    public function createAPI(Request $request)
    {
        try {
            $class = $this->create($request);
            return response()->json([
                'status' => 'Success',
                'message' => 'new class created',
                'data' => [
                    'class' => $class,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
        
    }

    private function delete($classCode)
    {        
        return DB::transaction(function () use ($classCode) {
            $courseClass = CourseClass::where('class_code', $classCode)->with('joinclass')->firstOrFail();
            if ($courseClass->joinClass != null)
                JoinClass::destroy(collect($courseClass->join_class)->pluck('id'));                
            $courseClass->delete();
        });
    }

    public function deleteUI($classCode)
    {
        try {
            $this->delete($classCode);
            return redirect()->route('getAllClass');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => $e->getTrace(),
            ]);
        }
    }

    public function deleteAPI($classCode)
    {
        try {
            $this->delete($classCode);
            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => $e->getTrace(),
            ]);
        }
    } 

    public function edit($id)
    {
        try {
            $courseClass = CourseClass::with('joinClass')->where('id',$id)->firstOrFail();
            if ($courseClass->joinClass != null) {
                $client = new Client();
                $courseClass->joinClass->each(function ($e) use ($client) {
                    $response = $client->request('GET', "http://127.0.0.1:8080/api/users/{$e->student_user_id}");
                    $e->data = json_decode($response->getBody());
                });
            }
            return view('edit', compact('courseClass'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function update(Request $request, $id)
    {
        $courseClass = CourseClass::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'thumbnail_img' => 'nullable|string',
            'class_code' => 'string|max:255',
            'syllabus_id' => 'integer',
            'settings' => 'nullable|array',
            'creator_user_id' => 'integer'
        ]);

        if ($validator->fails()) {
            throw new \Exception ($validator->errors(), 402);
        }

        $courseClass->update($request->only([
            'name',
            'thumbnail_img',
            'class_code',
            'syllabus_id',
            'settings',
            'creator_user_id'
        ]));
        return $courseClass;
    }

    public function updateUI(Request $request, $id)
    {
        try {
            $this->update($request, $id);
            return redirect()->route('getAllClass')->with('success', 'Course class updated successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function updateAPI(Request $request, $id)
    {
        try {
            $data = $this->update($request, $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Course Class berhasil diupdate',
                'data' => [
                    'class' => $data,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('getAllClass')->with('error', 'Please enter a search term.');
        }

        $results = CourseClass::search($query);

        return view('search', compact('results', 'query'));
    }

    public function getClassesBySearchName($name)
    {
        try {
            // Ganti %2B dengan karakter +
            $name = str_replace('%2B', '+', $name);
            $courseName = urldecode($name);

            $courses = CourseClass::where('name', 'LIKE', "%$courseName%")->get();

            if ($courses->isEmpty()) {
                return response()->json(['error' => 'Course Classes tidak ditemukan'], 404);
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
            $courses = CourseClass::where('id', $courseId)->get();
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
