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
            'question_type' => 'required|in:mcq,text',
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
        // 1. Validate everything (Question + Options)
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'question_text' => 'required',
            'question_type' => 'required|in:mcq,text',
            'marks' => 'required|integer|min:1',
            // Validate nested options array
            'options' => 'required_if:question_type,mcq|array|min:2',
            'options.*.id' => 'nullable',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'boolean',
        ]);

        // 2. Update Question Basic Info
        $question->update([
            'exam_id' => $request->exam_id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'marks' => $request->marks,
        ]);

        // 3. Sync Options
        if ($request->question_type === 'mcq') {
            $incomingIds = collect($request->options)->pluck('id')->filter()->toArray();

            // Delete options no longer in the form
            $question->options()->whereNotIn('id', $incomingIds)->delete();

            // Update existing or create new
            foreach ($request->options as $optionData) {
                $question->options()->updateOrCreate(
                    ['id' => $optionData['id'] ?? null],
                    [
                        'option_text' => $optionData['text'],
                        'is_correct' => (bool) ($optionData['is_correct'] ?? false),
                    ]
                );
            }
        } else {
            // If type changed to text, remove all options
            $question->options()->delete();
        }

        return redirect()->route('questions.index')->with('success', 'Question updated successfully');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted');
    }
}
