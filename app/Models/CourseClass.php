<?php

namespace App\Models;

use App\Models\JoinClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseClass extends Model
{
    use HasFactory;

    protected $table = 'course_classes';

    protected $fillable = [
        'course_id',
        'name',
        'thumbnail_img',
        'class_code',
        'creator_user_id',
        'syllabus_id',
        'settings',
    ];

    public function joinClass()
    {
        return $this->belongsTo(JoinClass::class, 'course_class_id');
    }

    public static function search($query)
    {
        return self::where('name', 'like', '%' . $query . '%')
            ->orWhere('id', $query)
            ->get();
    }

    public function edit()
    {
        return self::find($this->id);
    }

}
