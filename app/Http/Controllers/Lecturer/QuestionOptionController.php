<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;

class QuestionOptionController extends Controller
{
    /**
     * Show edit options page
     */
    public function edit(Question $question)
    {
        $question->load(['exam', 'options']);

        return inertia('Lecturer/QuestionOptions/Edit', [
            'question' => $question,
            'options' => $question->options,
        ]);
    }

    /**
     * Update MCQ options
     */
    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'options' => 'required|array|min:2',
            'options.*.id' => 'nullable', // Removed exists check here to handle it manually
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'boolean',
        ]);

        // 1. Validation for exactly one correct answer
        $correctCount = collect($data['options'])->where('is_correct', true)->count();
        if ($correctCount !== 1) {
            return back()->withErrors(['options' => 'You must select exactly one correct answer.']);
        }

        // 2. Identify IDs to keep vs delete
        $incomingIds = collect($data['options'])->pluck('id')->filter()->toArray();

        // Delete options that are no longer in the list
        $question->options()->whereNotIn('id', $incomingIds)->delete();

        // 3. Update or Create
        foreach ($data['options'] as $option) {
            $question->options()->updateOrCreate(
                ['id' => $option['id'] ?? null], // If ID exists, update. If null, create.
                [
                    'option_text' => $option['text'],
                    'is_correct' => (bool) ($option['is_correct'] ?? false), // Force boolean cast
                ]
            );
        }

        return redirect()->route('questions.index')->with('success', 'Options updated successfully.');
    }
}
