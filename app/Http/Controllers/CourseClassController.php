<?php

namespace App\Http\Controllers;

use App\Models\JoinClass;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isEmpty;

class CourseClassController extends Controller
{
    //fungsi delete class
    public function deleteClass($classCode)
    {
        try {
            DB::transaction(function () use ($classCode) {
                $courseClass = CourseClass::where('class_code', $classCode)->with('joinclass')->firstOrFail();
                if ($courseClass->joinClass != null)
                    JoinClass::destroy(collect($courseClass->join_class)->pluck('id'));
                $courseClass->delete();
            });
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

    public function showCreateForm()
    {
    $courses = Http::get('http://localhost:5000/courses')->json()['syllabi'];
    $syllabi = Http::get('http://localhost:5000/syllabi')->json()['syllabi'];

    return view('create', compact('courses', 'syllabi'));
    }

    public function create(Request $request)
    {
        $CourseClass = CourseClass::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
            // 'thumbnail_img'=> $request->thumbnail_img,
            'class_code' => $request->class_code,
            'creator_user_id' => $request->creator_user_id,
            'syllabus_id' => $request->syllabus_id,
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

        // return response()->json([
        //     'status' => 'Success',
        //     'message' => 'All class grabbed',
        //     'data'=>[
        //         'classes' => $CourseClass,
        //     ]
        // ]);

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
        return response()->json([
            'status' => 'Success',
            'message' => 'All class grabbed',
            'data' => [
                'classes' => $CourseClass,
            ]
        ], 200);
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

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('getAllClass')->with('error', 'Please enter a search term.');
        }

        $results = CourseClass::search($query);

        return view('course_classes.search', compact('results', 'query'));
    }

}
