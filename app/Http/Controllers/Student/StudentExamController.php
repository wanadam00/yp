<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;

class StudentExamController extends Controller
{
    public function index()
    {
        $studentId = auth()->id();

        $exams = Exam::whereHas('classes.students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })
            ->with(['classes', 'attempts' => function ($q) use ($studentId) {
                // Only get the attempt for the logged-in student
                $q->where('student_id', $studentId)->latest();
            }])
            ->latest()
            ->get()
            ->map(function ($exam) {
                $latestAttempt = $exam->attempts->first();
                $exam->attempt_status = $latestAttempt ? $latestAttempt->status : null;
                $exam->attempt_id = $latestAttempt ? $latestAttempt->id : null;

                $startTime = \Carbon\Carbon::parse($exam->start_time);
                $endTime = $startTime->copy()->addMinutes($exam->duration_minutes);
                $now = now();

                // Is it currently within the window?
                $exam->is_opened = $now->greaterThanOrEqualTo($startTime) && $now->lessThanOrEqualTo($endTime);

                // Has the exam completely finished for everyone?
                $exam->is_expired = $now->greaterThan($endTime);

                $exam->formatted_start = $startTime->format('d M, h:i A');

                return $exam;
            });

        return inertia('Student/Exams/Index', compact('exams'));
    }

    public function start(Exam $exam)
    {
        if (now()->lessThan($exam->start_time)) {
            return back()->with('error', 'This exam has not started yet.');
        }
        $attempt = ExamAttempt::firstOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => auth()->id(),
            ],
            [
                'started_at' => now(),
                'status' => 'in_progress',
            ]
        );

        return redirect()->route('student.exams.show', $exam);
    }

    public function show(Exam $exam)
    {
        $exam->load('questions.options');
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            return redirect()->route('student.exams.index');
        }

        // Convert to Carbon object if it's currently a string
        $startTime = \Carbon\Carbon::parse($attempt->started_at);

        $durationSeconds = $exam->duration_minutes * 60;
        $expiryTime = $startTime->copy()->addSeconds($durationSeconds);
        $remainingSeconds = max(0, now()->diffInSeconds($expiryTime, false));

        return inertia('Student/Exams/Show', [
            'exam' => $exam,
            'attempt' => $attempt,
            'initialRemainingSeconds' => $remainingSeconds
        ]);
    }
}
