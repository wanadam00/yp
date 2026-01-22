<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
    exams: {
        type: Array,
        required: true
    }
})

const deleteExam = (id) => {
    if (confirm('Are you sure you want to delete this exam?')) {
        Inertia.delete(`/exams/${id}`)
    }
}
</script>

<template>

    <AppLayout>
        <div class="p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                    My Exams
                </h1>
                <Link href="/exams/create"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Exam
                </Link>
            </div>

            <!-- Empty State -->
            <div v-if="exams.length === 0"
                class="p-8 text-center text-gray-500 bg-white rounded-lg shadow dark:bg-gray-800 dark:text-gray-400">
                No exams created yet.
            </div>

            <!-- Exam List -->
            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="exam in exams" :key="exam.id"
                    class="p-5 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ exam.title }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Subject: {{ exam.subject?.subject_name ?? '-' }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Duration: {{ exam.duration_minutes }} minutes
                    </p>
                    <div class="flex flex-wrap justify-end gap-2 mt-4">
                        <Link :href="`/exams/${exam.id}/assign`"
                            class="px-3 py-1 text-sm text-orange-600 border border-orange-600 rounded hover:bg-orange-50 transition">
                            Assign
                        </Link>

                        <Link :href="`/exams/${exam.id}/preview`"
                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded hover:bg-emerald-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Preview
                        </Link>

                        <Link :href="`/exams/${exam.id}/edit`"
                            class="px-3 py-1 text-sm text-blue-600 border border-blue-600 rounded hover:bg-blue-50">
                            Edit
                        </Link>

                        <button @click="deleteExam(exam.id)"
                            class="px-3 py-1 text-sm text-red-600 border border-red-600 rounded hover:bg-red-50">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
