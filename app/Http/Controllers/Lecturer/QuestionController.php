<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Exam;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('exam')
            ->latest()
            ->get();
        return inertia('Lecturer/Questions/Index', ['questions' => $questions]);
    }

    public function create()
    {
        $exams = Exam::with('subject')->get();
        return inertia('Lecturer/Questions/Create', ['exams' => $exams]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'question_text' => 'required',
            'question_type' => 'required|in:mcq,tq',
            'marks' => 'required|integer|min:1',
        ]);

        $question = Question::create([
            'exam_id' => $request->exam_id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'marks' => $request->marks,
        ]);

        if ($request->question_type === 'mcq') {
            foreach ($request->options as $option) {
                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        }

        // return back()->with('success', 'Question added');
        return redirect()->route('questions.index')->with('success', 'Question added');
    }

    public function show(Question $question)
    {
        return inertia('Lecturer/Questions/Show', ['question' => $question->load('exam', 'options')]);
    }

    public function edit(Question $question)
    {
        $exams = Exam::with('subject')->get();

        $question->load('options');

        return inertia('Lecturer/Questions/Edit', [
            'question' => $question,
            'exams' => $exams,
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'question_text' => 'required',
            'question_type' => 'required|in:mcq,tq',
            'marks' => 'required|integer|min:1',
        ]);

        // 1. Fill the data
        $question->fill([
            'exam_id' => $request->exam_id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'marks' => $request->marks,
        ]);

        // 2. Force the save even if Laravel thinks nothing changed
        $question->save();

        // 3. Handle Options (Delete and Re-create to ensure sync)
        // This part MUST run even if the question type didn't change
        if ($request->question_type === 'mcq') {
            // Clear old ones
            $question->options()->delete();

            // Create new ones from the request
            if ($request->has('options')) {
                foreach ($request->options as $option) {
                    if (!empty($option['text'])) {
                        $question->options()->create([
                            'option_text' => $option['text'],
                            'is_correct' => filter_var($option['is_correct'], FILTER_VALIDATE_BOOLEAN),
                        ]);
                    }
                }
            }
        } else {
            // If it's TQ, ensure no MCQ options remain
            $question->options()->delete();
        }

        return redirect()->route('questions.index')->with('success', 'Updated successfully');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted');
    }
}
