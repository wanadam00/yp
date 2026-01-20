<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class StudentExamResultController extends Controller
{
    public function submit(Request $request, ExamAttempt $attempt)
    {
        $answers = $request->input('answers'); // Format: [question_id => option_id]

        foreach ($request->answers as $questionId => $optionId) {
            $option = QuestionOption::find($optionId);

            StudentAnswer::create([
                'exam_attempt_id'    => $attempt->id,
                'question_id'        => $questionId,
                'selected_option_id' => $optionId,
                'is_correct'         => $option->is_correct, // This MUST be saved here
            ]);
        }

        $attempt->update(['status' => 'submitted', 'submitted_at' => now()]);

        return redirect()->route('student.results.show', $attempt->id);
    }

    public function show(ExamAttempt $attempt)
    {
        // Load answers PLUS the selectedOption relationship
        $attempt->load(['exam.questions.options', 'answers.selectedOption']);

        $totalQuestions = $attempt->exam->questions->count();

        // Calculate score by looking THROUGH the relationship to the option
        $correctAnswers = $attempt->answers->filter(function ($answer) {
            // Access is_correct from the QuestionOption model via the relationship
            return $answer->selectedOption && $answer->selectedOption->is_correct;
        })->count();

        return inertia('Student/Results/Show', [
            'attempt' => $attempt,
            'score' => [
                'correct' => $correctAnswers,
                'total' => $totalQuestions,
                'percentage' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0
            ]
        ]);
    }
}
