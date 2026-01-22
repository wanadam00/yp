<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    attempt: Object
})

// Initialize form with all answers that need grading (TQ type)
const form = useForm({
    // Map only TQ questions to the form data
    grades: props.attempt.answers
        .filter(a => a.question.question_type === 'tq')
        .map(a => ({
            id: a.id,
            marks_awarded: a.marks_awarded
        }))
});

const saveAllGrades = () => {
    form.patch(`/grading/attempts/${props.attempt.id}/bulk-grade`, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Show a success notification
        }
    });
};
</script>

<template>
    <AppLayout>
        <div class="max-w-5xl p-6 mx-auto">
            <header
                class="mb-8 flex justify-between items-center sticky top-0 bg-gray-50 dark:bg-gray-900 py-4 z-20 border-b dark:border-gray-700">
                <div>
                    <!-- <Link href="/grading" class="text-sm text-indigo-600 font-bold">← Back to Submissions</Link> -->
                    <h1 class="text-2xl font-black mt-2">{{ attempt.exam?.title }}</h1>
                    <p class="text-gray-500 text-sm">Student: {{ attempt.student?.name }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right mr-4">
                        <span class="block text-xs text-gray-400 uppercase font-bold">Current Total</span>
                        <span class="text-2xl font-black text-indigo-600">
                            {{attempt.answers.reduce((acc, a) => acc + Number(a.marks_awarded), 0)}} Marks
                        </span>
                    </div>
                    <button @click="saveAllGrades" :disabled="form.processing"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save All Grades' }}
                    </button>
                </div>
            </header>

            <div class="space-y-6">
                <div v-for="(answer, index) in attempt.answers" :key="answer.id"
                    class="p-6 bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 shadow-sm">

                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase">
                            Question {{ index + 1 }} ({{ answer.question.question_type.toUpperCase() }})
                        </span>
                        <span class="text-xs font-medium text-gray-500">Max Marks: {{ answer.question.marks }}</span>
                    </div>

                    <p class="text-lg font-bold mb-4">{{ answer.question.question_text }}</p>

                    <div v-if="answer.question.question_type === 'mcq'"
                        class="text-sm p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                        <p class="text-gray-500 italic">Student chose:
                            <span class="font-bold"
                                :class="answer.marks_awarded > 0 ? 'text-green-600' : 'text-red-600'">
                                {{ answer.selected_option?.option_text || 'No answer' }}
                            </span>
                        </p>
                    </div>

                    <div v-else class="space-y-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border dark:border-gray-700">
                            <span class="text-[10px] font-black uppercase text-indigo-500">Student Response:</span>
                            <p class="mt-2 text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ answer.answer_text
                                }}</p>
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t dark:border-gray-700">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">
                                    Assign Marks (0 - {{ answer.question.marks }})
                                </label>
                                <input type="number" step="0.1"
                                    v-model="form.grades.find(g => g.id === answer.id).marks_awarded"
                                    :max="answer.question.marks"
                                    class="w-32 p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
