<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    classRoom: Object,    // The classroom we are looking at
    subjects: Array   // The list of subjects for this class
})

const form = useForm({})

const deleteSubject = (id) => {
    if (confirm('Are you sure you want to delete this subject?')) {
        // This matches Route::resource('subjects').destroy
        form.delete(`/subjects/${id}`, {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <AppLayout>
        <div class="py-12 px-4">
            <div class="max-w-6xl mx-auto">

                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <!-- <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                            {{ classRoom.class_name }} Subjects
                        </h1> -->
                        <p class="text-gray-500 mt-1">Manage curriculum and exams for this class.</p>
                    </div>

                    <Link :href="`/subjects/create`"
                        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Subject
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Subjects</p>
                        <p class="text-3xl font-black text-indigo-600">{{ subjects.length }}</p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Subject
                                    Name</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">
                                    Linked Exams</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr v-for="subject in subjects" :key="subject.id"
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-900/40 transition">
                                <td class="px-6 py-5">
                                    <span class="font-bold text-gray-900 dark:text-white text-lg block">
                                        {{ subject.subject_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold uppercase">
                                        {{ subject.exams_count }} Exams
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <Link :href="`/subjects/${subject.id}/edit`"
                                            class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                                            Edit
                                        </Link>

                                        <span class="text-gray-300">|</span>

                                        <button @click="deleteSubject(subject.id)"
                                            class="text-sm font-bold text-red-500 hover:text-red-700 transition">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="subjects.length === 0">
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">
                                    No subjects created for this class yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
