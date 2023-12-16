<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\JoinClass;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CourseClassController extends Controller
{
    public function deleteClass($classCode)
    {
        try {
            DB::transaction(function () use ($classCode) {
                $courseClass = CourseClass::where('class_code', $classCode)->with('joinclass')->firstOrFail();
                if ($courseClass->joinClass != null)
                    JoinClass::destroy(collect($courseClass->join_class)->pluck('id'));
                $courseClass->delete();
            });
            
            return redirect()->route('getAllClass');
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
        CourseClass::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
            'class_code' => $request->class_code,
            'creator_user_id' => $request->creator_user_id,
            'syllabus_id' => $request->syllabus_id,
        ]);

        return redirect()->route('getAllClass');
    }

    public function getAllClass()
    {
        $CourseClass = CourseClass::all();
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
        return view('getAll', compact('result'));
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
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $courseClass = CourseClass::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'class_code' => 'required|string|max:255',
            'settings' => 'nullable|string|max:255',
        ]);
        $courseClass->update($request->all());
        return redirect()->route('getAllClass')->with('success', 'Course class updated successfully');
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
}
