<?php

namespace App\Http\Controllers;

use App\Models\JoinClass;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseClassController extends Controller
{
    //fungsi delete class
    public function deleteClass($id)
    {
        try {
            DB::transaction(function () use ($id){
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
}
