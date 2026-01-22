<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { computed } from 'vue'

const props = defineProps({
    // Receives 'subject' => Subject::with('classRoom')->get() from your controller
    subject: Array
})

// Extract unique classes from the subjects list to populate the dropdown
const availableClasses = computed(() => {
    const classes = props.subject.map(s => s.class_room);
    // Remove duplicates based on ID
    return Array.from(new Map(classes.map(item => [item['id'], item])).values());
})

const form = useForm({
    subject_name: '',
    class_id: '' // User will select this from the dropdown
})

const submit = () => {
    form.post('/subjects', {
        onSuccess: () => {
            form.reset()
        }
    })
}
</script>

<template>
    <AppLayout>

        <Head title="Create Subject" />

        <div class="py-12 px-4">
            <div
                class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border dark:border-gray-700">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold dark:text-white text-center">Create New Subject</h1>
                    <p class="text-center text-gray-500 text-sm">Select a class and enter the subject name.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target
                            Class</label>
                        <select v-model="form.class_id"
                            class="w-full px-4 py-2 rounded-lg border-gray-200 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 transition"
                            :class="{ 'border-red-500': form.errors.class_id }">
                            <option value="" disabled>-- Select a Class --</option>
                            <option v-for="item in availableClasses" :key="item.id" :value="item.id">
                                {{ item.class_name }}
                            </option>
                        </select>
                        <p v-if="form.errors.class_id" class="mt-2 text-sm text-red-600">{{ form.errors.class_id }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Subject
                            Name</label>
                        <input type="text" v-model="form.subject_name" placeholder="e.g. Mathematics"
                            class="w-full px-4 py-2 rounded-lg border-gray-200 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 transition"
                            :class="{ 'border-red-500': form.errors.subject_name }" />
                        <p v-if="form.errors.subject_name" class="mt-2 text-sm text-red-600">{{ form.errors.subject_name
                            }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t dark:border-gray-700">
                        <Link :href="form.class_id ? `/subjects?class_id=${form.class_id}` : '/classes'"
                            class="text-gray-500 hover:underline text-sm">
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
