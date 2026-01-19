<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

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

        return redirect()->route('exams.index', $exam->id);
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

        return redirect()->route('exams.index', $exam->id);
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('exams.index');
    }
}
