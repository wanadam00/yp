<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Exam;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request, Exam $exam)
    {
        $request->validate([
            'question_text' => 'required',
            'question_type' => 'required|in:mcq,text',
            'marks' => 'required|integer|min:1',
        ]);

        $question = Question::create([
            'exam_id' => $exam->id,
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

        return back()->with('success', 'Question added');
    }
}
