<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_name',
        'lecturer_id',
    ];

    protected $table = 'class_rooms';

    // Class → Lecturer
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    // Class → Students
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'class_students',
            'class_id',
            'student_id'
        );
    }

    // Class → Subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'class_id');
    }

    // Class → Exams
    public function exams()
    {
        return $this->belongsToMany(
            Exam::class,
            'exam_classes',
            'class_id',
            'exam_id'
        );
    }
}
