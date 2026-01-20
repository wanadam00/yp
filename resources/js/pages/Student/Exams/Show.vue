<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    exam: Object,
    attempt: Object // Add this
})

const form = useForm({
    answers: {}
})

const submitExam = () => {
    if (confirm('Are you sure you want to submit your exam?')) {
        // Use the Attempt ID as required by your route
        form.post(`/student/exam-attempts/${props.attempt.id}/submit`)
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-3xl mx-auto">
            <div
                class="flex items-center justify-between mb-8 sticky top-0 bg-gray-50 dark:bg-gray-900 py-4 z-10 border-b dark:border-gray-700">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ exam.title }}</h1>
                <button @click="submitExam"
                    class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 shadow-md">
                    Submit Exam
                </button>
            </div>

            <div class="space-y-8">
                <div v-for="(question, index) in exam.questions" :key="question.id"
                    class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                    <div class="flex items-center mb-4">
                        <span
                            class="px-3 py-1 text-xs font-bold text-blue-700 uppercase bg-blue-100 rounded-full dark:bg-blue-900/30 dark:text-blue-400">
                            Question {{ index + 1 }}
                        </span>
                        <span class="ml-auto text-sm text-gray-500">{{ question.marks }} Marks</span>
                    </div>

                    <p class="mb-6 text-xl font-medium text-gray-800 dark:text-gray-100">
                        {{ question.question_text }}
                    </p>

                    <div class="space-y-3">
                        <label v-for="(option, optIndex) in question.options" :key="option.id"
                            class="flex items-center p-4 border rounded-lg cursor-pointer transition-all hover:bg-gray-50 dark:hover:bg-gray-700"
                            :class="form.answers[question.id] == option.id
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 ring-1 ring-blue-500'
                                : 'border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40'">

                            <input type="radio" :name="'question-' + question.id" :value="option.id"
                                v-model="form.answers[question.id]" class="hidden" />

                            <span
                                class="flex items-center justify-center w-8 h-8 mr-3 text-sm font-bold border rounded-full shadow-sm bg-white dark:bg-gray-800"
                                :class="form.answers[question.id] == option.id ? 'text-blue-600 border-blue-500' : 'text-gray-500 dark:border-gray-600'">
                                {{ String.fromCharCode(65 + optIndex) }}
                            </span>

                            <span class="text-gray-700 dark:text-gray-300">{{ option.option_text }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
