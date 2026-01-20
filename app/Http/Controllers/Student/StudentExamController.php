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
                // Attach the latest attempt status to each exam object for easier access in Vue
                $latestAttempt = $exam->attempts->first();
                $exam->attempt_status = $latestAttempt ? $latestAttempt->status : null;
                $exam->attempt_id = $latestAttempt ? $latestAttempt->id : null;
                return $exam;
            });

        return inertia('Student/Exams/Index', compact('exams'));
    }

    public function start(Exam $exam)
    {
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

        // Find the active attempt for this student and this exam
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        return inertia('Student/Exams/Show', [
            'exam' => $exam,
            'attempt' => $attempt // Pass the attempt here
        ]);
    }
}
