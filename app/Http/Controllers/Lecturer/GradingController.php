<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class GradingController extends Controller
{
    // List all submitted exams that need marking
    public function index()
    {
        $attempts = ExamAttempt::with(['exam', 'student'])
            ->where('status', 'submitted')
            ->latest()
            ->get();

        return inertia('Lecturer/Grading/Index', [
            'attempts' => $attempts
        ]);
    }

    // Show specific student attempt for marking
    public function show(ExamAttempt $attempt)
    {
        // Make sure 'exam' is loaded!
        $attempt->load(['exam', 'student', 'answers.question', 'answers.selectedOption']);
        // dd($attempt);

        return inertia('Lecturer/Grading/Show', [
            'attempt' => $attempt
        ]);
    }

    // Update the marks for a specific text answer
    public function update(Request $request, StudentAnswer $answer)
    {
        $request->validate([
            'marks_awarded' => "required|integer|min:0|max:{$answer->question->marks}",
        ]);

        $answer->update([
            'marks_awarded' => $request->marks_awarded
        ]);

        // return back()->with('success', 'Mark updated successfully');
        return redirect()->route('grading.index')->with('success', 'Mark updated successfully');
    }

    public function bulkGrade(Request $request, ExamAttempt $attempt)
    {
        // 1. Validate the array of grades
        $request->validate([
            'grades' => 'required|array',
            'grades.*.id' => 'required|exists:student_answers,id',
            'grades.*.marks_awarded' => 'required|numeric|min:0',
        ]);

        // 2. Loop through and update each answer
        foreach ($request->grades as $gradeData) {
            // Ensure the answer actually belongs to this attempt for security
            $answer = $attempt->answers()->find($gradeData['id']);

            if ($answer) {
                // Check against the specific question's max marks
                $maxMarks = $answer->question->marks;
                $awarded = min($gradeData['marks_awarded'], $maxMarks);

                $answer->update([
                    'marks_awarded' => $awarded
                ]);
            }
        }

        // 3. Optional: Update total score on the attempt table if you have a total_score column
        // $attempt->update(['total_score' => $attempt->answers()->sum('marks_awarded')]);

        return redirect()->route('grading.index')->with('success', 'All marks updated successfully');
    }
}
