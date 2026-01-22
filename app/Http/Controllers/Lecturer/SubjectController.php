<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // List subjects for a specific class
    public function index()
    {
        $subjects = Subject::with('classRoom')->latest()->get();

        return inertia('Lecturer/Subjects/Index', [
            'subjects' => $subjects
        ]);
    }

    // Show Create Page
    public function create()
    {
        // Capture class_id from the ?class_id=XX query parameter
        // $classId = $request->query('class_id');

        // Ensure the class exists
        // $classRoom = ClassRoom::findOrFail($classId);
        $subject = Subject::with('classRoom')->get();

        return inertia('Lecturer/Subjects/Create', [
            'subject' => $subject,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'class_id'     => 'required|exists:class_rooms,id',
        ]);

        Subject::create([
            'subject_name' => $request->subject_name,
            'class_id'     => $request->class_id,
        ]);

        // Redirect back to the index page WITH the class_id parameter
        return redirect()->route('subjects.index', ['class_id' => $request->class_id])
            ->with('success', 'Subject created successfully.');
    }

    // Show Edit Page
    public function edit(Subject $subject)
    {
        // Load the class relationship so we know which class this subject belongs to
        $subject->load('classRoom');
        return inertia('Lecturer/Subjects/Edit', [
            'subject' => $subject,
            'classRooms' => ClassRoom::all(),
        ]);
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'class_id'     => 'required|exists:class_rooms,id', // Keep existing class link
        ]);

        $subject->update([
            'subject_name' => $request->subject_name,
            'class_id'     => $request->class_id,
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    // Delete a subject
    public function destroy(Subject $subject)
    {
        // Optional: Check if subject has exams before deleting
        if ($subject->exams()->exists()) {
            return back()->with('error', 'Cannot delete subject that has associated exams.');
        }

        $subject->delete();

        return back()->with('success', 'Subject deleted successfully.');
    }
}
