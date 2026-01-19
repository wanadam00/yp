<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAnswer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'exam_attempt_id',
        'question_id',
        'selected_option_id',
        'answer_text',
        'marks_awarded',
    ];

    // Answer → Exam Attempt
    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    // Answer → Question
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    // Answer → Selected Option (MCQ)
    public function selectedOption()
    {
        return $this->belongsTo(
            QuestionOption::class,
            'selected_option_id'
        );
    }
}
