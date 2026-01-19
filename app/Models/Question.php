<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'exam_id',
        'question_text',
        'question_type',
        'marks',
    ];

    protected $casts = [
        'question_type' => 'string',
        'marks' => 'float',
    ];

    // Question → Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    // Question → Options (MCQ)
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    // Question → Student Answers
    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'question_id');
    }
}
