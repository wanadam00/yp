<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use App\Services\Exam\AutoMarkMcqService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamAttemptController extends Controller
{

    public function start(Exam $exam)
    {
        // Prevent multiple attempts
        $existing = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()->route('exams.take', $exam->id);
        }

        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'student_id' => auth()->id(),
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('exams.take', $exam->id);
    }

    public function take(Exam $exam)
    {
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $endTime = Carbon::parse($attempt->started_at)
            ->addMinutes($exam->duration_minutes);

        return inertia('Student/Exams/Take', [
            'exam' => $exam->load('questions.options'),
            'attempt' => $attempt,
            'endTime' => $endTime,
        ]);
    }

    public function submit(Request $request, Exam $exam, AutoMarkMcqService $autoMark)
    {
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        // Save answers
        foreach ($request->answers as $questionId => $answer) {
            StudentAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $questionId,
                ],
                [
                    'selected_option_id' => $answer['option_id'] ?? null,
                    'answer_text' => $answer['text'] ?? null,
                ]
            );
        }

        $attempt->update([
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        // ✅ Auto MCQ marking here
        $autoMark->mark($attempt);

        return redirect()->route('student.exams.index')
            ->with('success', 'Exam submitted successfully');
    }
}
