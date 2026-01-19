<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'status',
    ];

    // Attempt → Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    // Attempt → Student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Attempt → Answers
    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'exam_attempt_id');
    }
}
