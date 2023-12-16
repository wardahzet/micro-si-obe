<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JoinClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'student_user_id',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function students()
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }
}
