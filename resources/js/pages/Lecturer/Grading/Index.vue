<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    attempts: Array
})

// Helper to format date
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>

    <Head title="Grading Dashboard" />

    <AppLayout>
        <div class="p-6 max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white">Grading Dashboard</h1>
                    <p class="text-sm text-gray-500">Review and mark student submissions for descriptive questions.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Submissions</p>
                    <p class="text-3xl font-black text-gray-800 dark:text-white">{{ attempts.length }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Student</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Exam Title</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Date Submitted</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        <tr v-for="attempt in attempts" :key="attempt.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-9 w-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                        {{ attempt.student.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-gray-200">{{ attempt.student.name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ attempt.student.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                                {{ attempt.exam.title }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ formatDate(attempt.submitted_at) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link :href="`/grading/${attempt.id}`"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                    Review & Grade
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="attempts.length === 0">
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No submissions found to grade.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
