<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    exam: {
        type: Object,
        required: true
    },
    classes: {
        type: Array,
        required: true
    },
    assignedClassIds: {
        type: Array,
        default: () => []
    }
})

// Initialize form with currently assigned classes
const form = useForm({
    class_ids: [...props.assignedClassIds]
})

const toggleClass = (id) => {
    const index = form.class_ids.indexOf(id)
    if (index > -1) {
        form.class_ids.splice(index, 1)
    } else {
        form.class_ids.push(id)
    }
}

const submit = () => {
    form.post(`/exams/${props.exam.id}/assign`, {
        preserveScroll: true,
        onSuccess: () => {
            // Success handling is managed by the controller redirect
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="max-w-3xl p-6 mx-auto">
            <div class="mb-6">
                <!-- <Link :href="'/exams'" class="text-sm text-blue-600 hover:underline">
                    &larr; Back to Exams
                </Link> -->
                <h1 class="mt-2 text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Assign Exam to Classes
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Exam: <span class="font-semibold text-gray-800 dark:text-gray-200">{{ exam.title }}</span>
                </p>
            </div>

            <div class="bg-white border rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                <form @submit.prevent="submit">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">
                            Select Classes
                        </h2>

                        <div v-if="form.errors.class_ids" class="mb-4 text-sm text-red-600 font-medium">
                            {{ form.errors.class_ids }}
                        </div>

                        <div v-if="classes.length === 0" class="p-4 text-center bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <p class="text-gray-500">You don't have any classes created yet.</p>
                        </div>

                        <div v-else class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div v-for="classroom in classes" :key="classroom.id" @click="toggleClass(classroom.id)"
                                class="relative flex items-center p-4 border rounded-lg cursor-pointer transition-all"
                                :class="form.class_ids.includes(classroom.id)
                                    ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 ring-1 ring-blue-500'
                                    : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'">

                                <div class="flex items-center h-5">
                                    <input type="checkbox" :checked="form.class_ids.includes(classroom.id)"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                </div>

                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{ classroom.class_name ?? '' }}
                                    </span>
                                    <!-- <span class="block text-xs text-gray-500">
                                        {{ classroom.section ?? 'Main Section' }}
                                    </span> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t dark:border-gray-700 flex justify-end items-center space-x-4">
                        <Link :href="'/exams'"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                            Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 transition shadow-sm">
                            {{ form.processing ? 'Saving...' : 'Save Assignments' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
