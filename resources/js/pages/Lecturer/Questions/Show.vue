<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    question: {
        type: Object,
        required: true
    }
})

const deleteQuestion = (id) => {
    if (confirm('Are you sure you want to delete this question?')) {
        Inertia.delete(`/questions/${id}`)
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">
                Edit Question
            </h1>

            <div class="p-6 space-y-6">
                <section>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Question Text</h3>
                    <p
                        class="mt-2 text-lg text-gray-900 dark:text-gray-200 bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border dark:border-gray-700">
                        {{ question.question_text }}
                    </p>
                </section>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Exam</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-200">{{ question.exam?.title ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Type</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-200 capitalize">{{ question.question_type }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Marks</h3>
                        <p class="mt-1 text-gray-900 dark:text-gray-200 font-semibold">{{ question.marks }}</p>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700" />

                <section v-if="question.options && question.options.length > 0">
                    <h3 class="mb-4 text-sm font-medium text-gray-500 uppercase tracking-wider">Answer Options</h3>
                    <div class="space-y-3">
                        <div v-for="(option, index) in question.options" :key="option.id"
                            class="flex items-center p-3 rounded-md border" :class="option.is_correct
                                ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800'
                                : 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700'">

                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 mr-3 text-sm font-bold">
                                {{ String.fromCharCode(65 + index) }}
                            </span>

                            <span class="text-gray-800 dark:text-gray-200">{{ option.option_text }}</span>

                            <span v-if="option.is_correct"
                                class="ml-auto text-xs font-bold uppercase text-green-600 dark:text-green-400">
                                Correct Answer
                            </span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
