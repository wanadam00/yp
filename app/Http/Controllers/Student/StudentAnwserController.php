<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class StudentAnswerController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'answers' => 'required|array',
        ]);

        $attempt = ExamAttempt::where([
            'exam_id' => $data['exam_id'],
            'student_id' => auth()->id(),
        ])->firstOrFail();

        foreach ($data['answers'] as $answer) {
            StudentAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $answer['question_id'],
                ],
                [
                    'selected_option_id' => $answer['selected_option_id'] ?? null,
                    'answer_text' => $answer['answer_text'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Answers saved');
    }
}
