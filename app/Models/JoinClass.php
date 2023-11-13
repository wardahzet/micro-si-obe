<?php

namespace App\Models;

use App\Models\CourseClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JoinClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'student_user_id',
    ];

    /**
     * Get the related course class.
     */
    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

}
