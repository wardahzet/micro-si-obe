<?php

namespace App\Http\Controllers;

use App\Models\JoinClass;
use Illuminate\Support\Facades\DB;

class JoinClassController extends Controller
{
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
