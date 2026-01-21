<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'title',
        'description',
        'duration_minutes',
        'start_time',
        'end_time',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime', // Replace 'start_time' with your actual column name
    ];
    
    // Exam → Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Exam → Questions
    public function questions()
    {
        return $this->hasMany(Question::class, 'exam_id');
    }

    // Exam → Classes
    public function classes()
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'exam_classes',
            'exam_id',
            'class_id'
        );
    }

    // Exam → Attempts
    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class, 'exam_id');
    }

    // Exam → Creator (Lecturer)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
