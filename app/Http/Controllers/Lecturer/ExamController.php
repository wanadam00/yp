<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use App\Models\ExamClasses;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('subject')
            ->where('created_by', auth()->id())
            ->latest()
            ->get();

        return inertia('Lecturer/Exams/Index', compact('exams'));
    }

    public function create()
    {
        $subjects = Subject::whereHas('class', function ($q) {
            $q->where('lecturer_id', auth()->id());
        })->get();
        // dd($subjects);

        return inertia('Lecturer/Exams/Create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required',
            'title' => 'required',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $exam = Exam::create([
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('exams.index');
    }

    public function edit(Exam $exam)
    {
        $subjects = Subject::whereHas('class', function ($q) {
            $q->where('lecturer_id', auth()->id());
        })->get();

        return inertia('Lecturer/Exams/Edit', [
            'exam' => $exam,
            'subjects' => $subjects,
        ]);
    }


    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'subject_id' => 'required',
            'title' => 'required',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $exam->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'duration_minutes' => $request->duration_minutes,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('exams.index');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('exams.index');
    }

    public function assign(Exam $exam)
    {
        // Get classes that belong to the current lecturer
        $classes = ClassRoom::where('lecturer_id', auth()->id())->get();

        // Load already assigned classes
        $assignedClassIds = $exam->classes()->pluck('class_id')->toArray();
        // dd($classes);

        return inertia('Lecturer/Exams/Assign', [
            'exam' => $exam,
            'classes' => $classes,
            'assignedClassIds' => $assignedClassIds,
        ]);
    }

    /**
     * Store the exam-class assignments
     */
    public function storeAssignment(Request $request, Exam $exam)
    {
        $request->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:class_rooms,id',
        ]);

        // Remove old assignments
        ExamClasses::where('exam_id', $exam->id)->delete();

        // Insert new assignments
        foreach ($request->class_ids as $classId) {
            ExamClasses::create([
                'exam_id' => $exam->id,
                'class_id' => $classId,
            ]);
        }

        return redirect()->route('exams.index')->with('success', 'Exam assigned to classes successfully.');
    }
}
