<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, computed } from 'vue'

const props = defineProps({
    students: Array
})

const form = useForm({
    class_name: '',
    student_ids: []
})

// Search state for students
const searchQuery = ref('')

// Filter students based on search
const filteredStudents = computed(() => {
    return props.students.filter(student =>
        student.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        student.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const submit = () => {
    // form.post(route('class-student.store'))
    form.post('/class-student', {
        onSuccess: () => {
            form.reset()
        }
    })
}

// Toggle selection helper
const toggleStudent = (id) => {
    const index = form.student_ids.indexOf(id)
    if (index > -1) {
        form.student_ids.splice(index, 1)
    } else {
        form.student_ids.push(id)
    }
}
</script>

<template>
    <AppLayout>
        <div class="py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">New Classroom</h1>
                        <p class="text-gray-500">Organize your students into a new class group.</p>
                    </div>
                    <!-- <Link :href="'/class-student/index'" class="text-sm text-gray-600 hover:text-gray-900">
                        Cancel
                    </Link> -->
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Class Name
                        </label>
                        <input type="text" v-model="form.class_name" placeholder="e.g., Year 3 Biology - Section A"
                            class="w-full px-4 py-3 rounded-xl border-gray-200 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                        <p v-if="form.errors.class_name" class="mt-2 text-sm text-red-600">{{ form.errors.class_name }}
                        </p>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                Assign Students ({{ form.student_ids.length }} selected)
                            </label>

                            <input type="text" v-model="searchQuery" placeholder="Search by name or email..."
                                class="text-sm px-4 py-2 rounded-lg border-gray-200 dark:bg-gray-900 dark:border-gray-600 w-full md:w-64" />
                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[400px] overflow-y-auto p-2 pr-4 custom-scrollbar">
                            <div v-for="student in filteredStudents" :key="student.id"
                                @click="toggleStudent(student.id)"
                                class="flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                :class="form.student_ids.includes(student.id)
                                    ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20'
                                    : 'border-gray-50 dark:border-gray-700 hover:border-indigo-200'">
                                <div class="relative flex items-center justify-center w-5 h-5 mr-4">
                                    <input type="checkbox" :value="student.id" v-model="form.student_ids"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5" />
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ student.name
                                    }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ student.email }}</p>
                                </div>
                            </div>

                            <div v-if="filteredStudents.length === 0"
                                class="col-span-full py-10 text-center text-gray-500">
                                No students found matching "{{ searchQuery }}"
                            </div>
                        </div>
                        <p v-if="form.errors.student_ids" class="mt-2 text-sm text-red-600">{{ form.errors.student_ids
                        }}</p>
                    </div>

                    <div class="flex items-center justify-end">
                        <Link :href="'/class-student/index'" class="text-gray-500 hover:underline text-sm mr-4">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 disabled:opacity-50 shadow-lg shadow-indigo-200 dark:shadow-none transition">
                            {{ form.processing ? 'Processing...' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
