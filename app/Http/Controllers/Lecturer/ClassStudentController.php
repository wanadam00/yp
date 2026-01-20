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
            'student_ids' => 'array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $studentIds = $request->student_ids ?? [];

        // Delete removed students
        ClassStudent::where('class_id', $class_student->id)
            ->whereNotIn('student_id', $studentIds)
            ->delete();

        // Add/Update students
        foreach ($studentIds as $studentId) {
            ClassStudent::updateOrCreate([
                'class_id' => $class_student->id, // This will now be populated
                'student_id' => $studentId,
            ]);
        }

        return redirect()->route('class-student.index')
            ->with('success', 'Students updated successfully.');
    }
}
