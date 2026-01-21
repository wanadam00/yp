<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    attempt: Object,
    score: Object
})

// Helper to find the student's specific answer for a question
const getAnswer = (questionId) => {
    return props.attempt.answers.find(a => a.question_id === questionId)
}
</script>

<template>
    <AppLayout>
        <div class="max-w-4xl p-6 mx-auto">
            <div class="mb-8">
                <Link href="/student/exams" class="text-sm text-blue-600 hover:underline">
                    &larr; Back to My Exams
                </Link>

                <div
                    class="mt-4 p-8 bg-white border rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ attempt.exam.title }}</h1>
                        <p class="text-gray-500 mt-1">Submitted on {{ new Date(attempt.submitted_at).toLocaleString() }}
                        </p>

                        <div v-if="score.is_pending"
                            class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                            Manual Grading Pending
                        </div>
                    </div>

                    <div
                        class="mt-6 md:mt-0 text-center px-8 py-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border dark:border-gray-700">
                        <span class="block text-sm font-medium text-gray-500 uppercase tracking-wider">Total
                            Score</span>
                        <span class="text-4xl font-black text-blue-600">
                            {{ score.percentage }}%
                        </span>
                        <span class="block text-xs text-gray-400 mt-1">
                            {{ score.earned }} / {{ score.possible }} Marks
                        </span>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">Review Answers</h2>

            <div class="space-y-6 pb-20">
                <div v-for="(question, index) in attempt.exam.questions" :key="question.id"
                    class="p-6 bg-white border rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                    <div class="flex items-center mb-4">
                        <span class="text-sm font-bold text-gray-400 uppercase">Question {{ index + 1 }}</span>

                        <template v-if="question.question_type === 'mcq'">
                            <span v-if="getAnswer(question.id)?.marks_awarded > 0"
                                class="ml-auto px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                                Correct
                            </span>
                            <span v-else
                                class="ml-auto px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">
                                Incorrect
                            </span>
                        </template>

                        <template v-else>
                            <span
                                class="ml-auto px-3 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/40 dark:text-blue-300">
                                {{ getAnswer(question.id)?.marks_awarded }} / {{ question.marks }} Marks
                            </span>
                        </template>
                    </div>

                    <p class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-6">
                        {{ question.question_text }}
                    </p>

                    <div v-if="question.question_type === 'mcq'" class="grid gap-3">
                        <div v-for="option in question.options" :key="option.id"
                            class="flex items-center p-4 border rounded-lg transition-all" :class="[
                                option.is_correct ? 'border-green-500 bg-green-50 dark:bg-green-900/20 ring-1 ring-green-500' : 'border-gray-100 dark:border-gray-700',
                                (getAnswer(question.id)?.selected_option_id === option.id && !option.is_correct)
                                    ? 'border-red-500 bg-red-50 dark:bg-red-900/20 ring-1 ring-red-500' : ''
                            ]">

                            <div class="w-6 h-6 flex items-center justify-center rounded-full border text-xs font-bold mr-3"
                                :class="option.is_correct ? 'bg-green-500 text-white border-green-500' : 'bg-white dark:bg-gray-800 text-gray-400 border-gray-300'">
                                <span v-if="option.is_correct">✓</span>
                                <span v-else-if="getAnswer(question.id)?.selected_option_id === option.id">✕</span>
                            </div>

                            <span
                                :class="option.is_correct ? 'font-semibold text-green-800 dark:text-green-300' : 'text-gray-700 dark:text-gray-300'">
                                {{ option.option_text }}
                            </span>

                            <span v-if="getAnswer(question.id)?.selected_option_id === option.id"
                                class="text-[10px] font-bold uppercase tracking-tighter text-gray-400 ml-2">
                                (Your Choice)
                            </span>
                        </div>
                    </div>

                    <div v-else class="space-y-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border dark:border-gray-700 rounded-lg">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Your Answer:</span>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap italic">
                                {{ getAnswer(question.id)?.answer_text || 'No answer provided.' }}
                            </p>
                        </div>

                        <div v-if="question.options[0]"
                            class="p-4 border border-blue-100 bg-blue-50/30 dark:border-blue-900/30 dark:bg-blue-900/10 rounded-lg">
                            <span class="block text-[10px] font-bold text-blue-400 uppercase mb-2">Reference
                                Answer:</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                                {{ question.options[0].option_text }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
