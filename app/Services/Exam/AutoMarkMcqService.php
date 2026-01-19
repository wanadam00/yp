<?php

namespace App\Services\Exam;

use App\Models\ExamAttempt;

class AutoMarkMcqService
{
    public function mark(ExamAttempt $attempt): void
    {
        $attempt->load('answers.question.options');

        foreach ($attempt->answers as $answer) {

            if ($answer->question->question_type !== 'mcq') {
                continue;
            }

            $correctOptionId = $answer->question
                ->options
                ->where('is_correct', true)
                ->first()?->id;

            $answer->marks_awarded =
                $answer->selected_option_id === $correctOptionId
                ? $answer->question->marks
                : 0;

            $answer->save();
        }
    }
}
