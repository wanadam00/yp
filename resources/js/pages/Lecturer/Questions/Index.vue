<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
    questions: {
        type: Array,
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
        <div class="p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                    My Questions
                </h1>
                <Link href="/questions/create"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Question
                </Link>
            </div>

            <!-- Empty State -->
            <div v-if="questions.length === 0"
                class="p-8 text-center text-gray-500 bg-white rounded-lg shadow dark:bg-gray-800 dark:text-gray-400">
                No questions created yet.
            </div>

            <!-- Question List -->
            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="question in questions" :key="question.id"
                    class="p-5 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ question.question_text }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Exam: {{ question.exam?.title ?? '-' }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Type: {{ question.question_type }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Marks: {{ question.marks }}
                    </p>
                    <div class="flex justify-end mt-4 space-x-2">
                        <Link :href="`/questions/${question.id}/edit`"
                            class="px-3 py-1 text-sm text-blue-600 border border-blue-600 rounded hover:bg-blue-50 dark:hover:bg-gray-700">
                            Edit
                        </Link>
                        <Link :href="`/questions/${question.id}`"
                            class="px-3 py-1 text-sm text-blue-600 border border-blue-600 rounded hover:bg-blue-50 dark:hover:bg-gray-700">
                            Show
                        </Link>
                        <button @click="deleteQuestion(question.id)"
                            class="px-3 py-1 text-sm text-red-600 border border-red-600 rounded hover:bg-red-50 dark:hover:bg-gray-700">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
