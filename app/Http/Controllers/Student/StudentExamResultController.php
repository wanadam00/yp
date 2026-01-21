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
        // $request->answers is [question_id => value]
        $answers = $request->input('answers', []);

        foreach ($answers as $questionId => $value) {
            $question = Question::findOrFail($questionId);

            if ($question->question_type === 'mcq') {
                // Handle Multiple Choice
                $option = QuestionOption::find($value);
                $isCorrect = $option ? $option->is_correct : false;

                StudentAnswer::create([
                    'exam_attempt_id'    => $attempt->id,
                    'question_id'        => $questionId,
                    'selected_option_id' => $value,
                    'answer_text'        => null,
                    // Auto-calculate marks for MCQ
                    'marks_awarded'      => $isCorrect ? $question->marks : 0,
                ]);
            } else {
                // Handle Text Question (TQ)
                StudentAnswer::create([
                    'exam_attempt_id'    => $attempt->id,
                    'question_id'        => $questionId,
                    'selected_option_id' => null,
                    'answer_text'        => $value,
                    // TQ starts at 0 until lecturer reviews it
                    'marks_awarded'      => 0,
                ]);
            }
        }

        // Update attempt status
        $attempt->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        return redirect()->route('student.results.show', $attempt->id);
    }

    public function show(ExamAttempt $attempt)
    {
        // Load relationships to display the questions and student answers
        $attempt->load(['exam.questions.options', 'answers.question']);

        // 1. Calculate the Total Marks Earned (Sum of MCQ + TQ marks)
        $totalMarksEarned = $attempt->answers->sum('marks_awarded');

        // 2. Calculate the Total Possible Marks for the whole exam
        $totalPossibleMarks = $attempt->exam->questions->sum('marks');

        // 3. Count how many questions are still "Pending" (TQ questions with 0 marks)
        // Note: This is a simple check; you might want to add a 'is_marked' boolean to StudentAnswer later
        $hasPendingGrading = $attempt->exam->questions()
            ->where('question_type', 'tq')
            ->exists();

        return inertia('Student/Results/Show', [
            'attempt' => $attempt,
            'score' => [
                'earned' => $totalMarksEarned,
                'possible' => $totalPossibleMarks,
                'percentage' => $totalPossibleMarks > 0
                    ? round(($totalMarksEarned / $totalPossibleMarks) * 100, 2)
                    : 0,
                'is_pending' => $hasPendingGrading // Tells the student if TQ is still being reviewed
            ]
        ]);
    }
}
