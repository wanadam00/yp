<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamPreviewController extends Controller
{
    public function show(Exam $exam, Request $request)
    {
        $classId = $request->query('class_id');

        // 1. Load the questions and options for the preview
        // 2. Load the 'classes' relationship (this returns ClassRoom models)
        $exam->load(['subject', 'questions.options', 'classes']);

        return inertia('Lecturer/Exams/Preview', [
            'exam' => $exam,
            'assignedClasses' => $exam->classes, // This is now a collection of ClassRoom objects
            'selectedClassId' => (string) $classId,
        ]);
    }
}
