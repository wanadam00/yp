<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\ClassStudent;
use App\Models\User;
use Illuminate\Http\Request;

class ClassStudentController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::where('lecturer_id', auth()->id())->get();

        return inertia('Lecturer/Classes/Index', [
            'classes' => $classes,
        ]);
    }

    public function create()
    {
        $students = User::role('student')->get();

        return inertia('Lecturer/Classes/Create', [
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $class = ClassRoom::create([
            'class_name' => $request->class_name,
            'lecturer_id' => auth()->id(),
        ]);

        $studentIds = $request->student_ids ?? [];

        // Add/Update students to the class
        foreach ($studentIds as $studentId) {
            ClassStudent::create([
                'class_id' => $class->id,
                'student_id' => $studentId,
            ]);
        }
        return redirect()->route('class-student.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(ClassRoom $class_student) // Variable name matches resource
    {
        $students = User::role('student')->get();

        $assignedStudentIds = ClassStudent::where('class_id', $class_student->id)
            ->pluck('student_id')
            ->toArray();

        return inertia('Lecturer/Classes/AssignStudents', [
            'class_student' => $class_student, // Changed key
            'students' => $students,
            'assignedStudentIds' => $assignedStudentIds,
        ]);
    }

    public function update(Request $request, ClassRoom $class_student)
    {
        $request->validate([
            'class_name' => 'required|string|max:255', // Add this
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Update the Class Name
        $class_student->update([
            'class_name' => $request->class_name
        ]);

        $studentIds = $request->student_ids ?? [];

        // Sync Students (Delete removed ones)
        ClassStudent::where('class_id', $class_student->id)
            ->whereNotIn('student_id', $studentIds)
            ->delete();

        // Add new ones
        foreach ($studentIds as $studentId) {
            ClassStudent::updateOrCreate([
                'class_id' => $class_student->id,
                'student_id' => $studentId,
            ]);
        }

        return redirect()->route('class-student.index')
            ->with('success', 'Class details and students updated successfully.');
    }
}
