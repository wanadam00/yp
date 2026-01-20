<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
    exams: Array
})
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-5xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">My Exams</h1>

            <div v-if="exams.length === 0" class="..."> No exams assigned... </div>

            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="exam in exams" :key="exam.id"
                    class="p-5 bg-white border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ exam.title }}</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Duration: {{ exam.duration_minutes }} minutes
                        </p>
                    </div>

                    <div class="mt-6">
                        <Link v-if="!exam.attempt_status" :href="`/student/exams/${exam.id}/start`" method="post"
                            as="button"
                            class="w-full text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Start Exam
                        </Link>

                        <Link v-else-if="exam.attempt_status === 'in_progress'"
                            :href="`/student/exam-attempts/${exam.attempt_id}`"
                            class="w-full block text-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition">
                            Continue Exam
                        </Link>

                        <Link v-else-if="exam.attempt_status === 'submitted'"
                            :href="`/student/results/${exam.attempt_id}`"
                            class="w-full block text-center px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition">
                            View Result
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
