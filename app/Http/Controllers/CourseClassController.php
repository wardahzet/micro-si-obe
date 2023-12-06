<?php

namespace App\Http\Controllers;

use App\Models\JoinClass;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class CourseClassController extends Controller
{
    //fungsi delete class
    public function deleteClass($id)
    {
        try {
            DB::transaction(function () use ($id) {
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

    public function showCreateForm()
    {
    $courses = Http::get('http://localhost:5000/courses')->json()['syllabi'];
    $syllabi = Http::get('http://localhost:5000/syllabi')->json()['syllabi'];

    return view('create', compact('courses', 'syllabi'));
    }

    public function create(Request $request)
    {
        try{
            $course = Http::get('http://localhost:5000/courses/')->json()['syllabi'];
            $syllabi = Http::get('http://localhost:5000/syllabi/')->json()['syllabi'];
            if (CourseClass::where('class_code', $request->class_code)->first() != null)
                throw new \Exception ('class_code sudah ada');
            if ($course != null && $syllabi != null) {
                    
                    $CourseClass = CourseClass::create([
                    'course_id' => $request->course_id,
                    'syllabus_id' => $request->syllabus_id,
                    'name' => $request->name,
                    'class_code' => $request->class_code,
                    'creator_user_id' => $request->creator_user_id,
                    // 'settings'=> $request->settings,
                    //  'thumbnail_img'=> $request->thumbnail_img,      
                ]);


                // return response()->json([
                //     'status' => 'Success',
                //     'message' => 'new class created',
                //     'data' => [
                //         'class' => $CourseClass,
                //     ]
                // ], 200);
                
                return redirect()->route('getAllClass');

            }
           else throw new \Exception ("kelas\/syllabi tidak ditemukan");
        }catch (\Exception $e){
            return response()->json([
                'Status' => 'eror',
                'message' => $e->getMessage(),
            ],500);
        }

    }


    public function getAllClass()
    {
        try {
            $CourseClass = CourseClass::all();
            if($CourseClass->isEmpty()){
                return response()->json([
                    'eror'=> 'Kelas tidak ditemukan'
                ],400);
            }
            $classes = CourseClass::all();
    
            $result = [];
            foreach ($classes as $class){
                $syllabus = Http::get('http://localhost:5000/syllabi/' . $class->syllabus_id)->json();
                $course = Http::get('http://localhost:5000/courses/'. $class->course_id)->json() ;
    
                $result[] = [
                    'name' => $class->name,
                    'thumbnail_img' => $class->thumbnail_img,
                    'class_code' => $class->class_code,
                    'creator_user_id'=> $class->creator_user_id,
                    'settings'=> $class->settings,
                    'course' => $course,
                    'syllabus' => $syllabus,
                ];   
            
            } 
            //return response()->json($result);
            // return response()->json([
            //     'status' => 'Success',
            //     'message' => 'All class grabbed',
            //     'data' => [
            //         'classes' => $CourseClass,
            //     ]
            // ], 200);
          // dd($result);
            return view('getAll',compact('result'));

        }catch (\Exception $e){
            return response()->json([
                'Status' => 'eror',
                'message' => $e->getMessage(),
            ],500);
        }

    }

    public function editCourseClass(Request $request, $id)
    {
        try {
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
                return response()->json(['error' => $validator->errors()], 400);
            }

            $courseClass->update($request->only([
                'name',
                'thumbnail_img',
                'class_code',
                'syllabus_id',
                'settings',
                'creator_user_id'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'Course Class berhasil diupdate',
                'data' => [
                    'class' => $courseClass,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getClassesByCourseName($name)
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
        //
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
