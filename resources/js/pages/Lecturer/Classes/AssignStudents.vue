<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    class_student: Object,
    students: Array,
    assignedStudentIds: Array
})

// Search state
const search = ref('')

// Initialize form
const form = useForm({
    student_ids: [...props.assignedStudentIds]
})

// Filtered student list based on search input
const filteredStudents = computed(() => {
    return props.students.filter(student =>
        student.name.toLowerCase().includes(search.value.toLowerCase()) ||
        student.email.toLowerCase().includes(search.value.toLowerCase())
    )
})

const toggleStudent = (id) => {
    const index = form.student_ids.indexOf(id)
    if (index > -1) {
        form.student_ids.splice(index, 1)
    } else {
        form.student_ids.push(id)
    }
}

const submit = () => {
    form.put(`/class-student/${props.class_student.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: alert user
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="max-w-4xl p-6 mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <!-- <Link href="/classes" class="text-sm text-blue-600 hover:underline">
                        &larr; Back to Classes
                    </Link> -->
                    <h1 class="mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100">
                        Assign Students to {{ props.class_student.class_name }}
                    </h1>
                </div>

                <div class="text-right">
                    <p class="text-sm font-medium text-gray-500">
                        Selected: {{ form.student_ids.length }} students
                    </p>
                </div>
            </div>

            <div class="bg-white border rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input v-model="search" type="text" placeholder="Search by name or email..."
                            class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200" />
                    </div>
                </div>

                <form @submit.prevent="submit">
                    <div class="p-6">
                        <div v-if="filteredStudents.length > 0" class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div v-for="student in filteredStudents" :key="student.id"
                                @click="toggleStudent(student.id)"
                                class="flex items-center p-3 border rounded-lg cursor-pointer transition-all" :class="form.student_ids.includes(student.id)
                                    ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 ring-1 ring-blue-500'
                                    : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'">

                                <input type="checkbox" :checked="form.student_ids.includes(student.id)"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    @click.stop="toggleStudent(student.id)" />

                                <div class="ml-3 overflow-hidden">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {{ student.name }}
                                    </span>
                                    <span class="block text-xs text-gray-500 truncate">
                                        {{ student.email }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-else class="p-12 text-center">
                            <p class="text-gray-500">No students found matching your search.</p>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t dark:border-gray-700 flex justify-end">
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 transition shadow-sm">
                            {{ form.processing ? 'Saving...' : 'Update Student List' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
