<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
    classes: {
        type: Array,
        required: true
    }
})

const deleteClass = (id) => {
    if (confirm('Are you sure you want to delete this class? This will remove all student assignments.')) {
        Inertia.delete(`/classes/${id}`)
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                    My Classes
                </h1>
                <Link href="/class-student/create"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Class
                </Link>
            </div>

            <div v-if="classes.length === 0"
                class="p-12 text-center text-gray-500 bg-white rounded-xl shadow-sm border-2 border-dashed dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                <p class="text-lg">No classes found.</p>
                <p class="text-sm">Create your first class to start assigning students and exams.</p>
            </div>

            <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="classroom in classes" :key="classroom.id"
                    class="flex flex-col bg-white border rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md transition-shadow">

                    <div class="p-5">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                                    {{ classroom.class_name }}
                                </h2>
                                <!-- <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Section: {{ classroom.section ?? 'N/A' }}
                                </p> -->
                            </div>
                            <!-- <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Class ID: {{ classroom.id }}
                            </span> -->
                        </div>
                    </div>

                    <div
                        class="px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-700 rounded-b-xl flex flex-wrap gap-2 justify-end">
                        <!-- <Link :href="`/class-student/${classroom.id}/edit`"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md hover:bg-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Students
                        </Link> -->

                        <Link :href="`/class-student/${classroom.id}/edit`"
                            class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 transition">
                            Edit
                        </Link>

                        <button @click="deleteClass(classroom.id)"
                            class="px-3 py-1.5 text-xs font-medium text-red-600 bg-white border border-red-300 rounded-md hover:bg-red-50 dark:bg-gray-800 dark:border-red-900/30 transition">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
