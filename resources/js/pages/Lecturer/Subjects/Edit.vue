<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    subject: Object,      // The specific subject being edited
    classRooms: Array     // All available classes for the dropdown
})

const form = useForm({
    subject_name: props.subject.subject_name,
    class_id: props.subject.class_id
})

const submit = () => {
    // Standard PUT request for updates in Laravel resources
    form.put(`/subjects/${props.subject.id}`, {
        onSuccess: () => {
            // Optional: logic after success
        }
    })
}
</script>

<template>
    <AppLayout>

        <Head title="Edit Subject" />

        <div class="py-12 px-4">
            <div
                class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border dark:border-gray-700">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold dark:text-white text-center">Edit Subject</h1>
                    <p class="text-center text-gray-500 text-sm">Update the details for "{{ subject.subject_name }}".
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Target
                            Class</label>
                        <select v-model="form.class_id"
                            class="w-full px-4 py-2 rounded-lg border-gray-200 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 transition"
                            :class="{ 'border-red-500': form.errors.class_id }">
                            <option value="" disabled>-- Select a Class --</option>
                            <option v-for="item in classRooms" :key="item.id" :value="item.id">
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
                        <Link :href="`/subjects?class_id=${subject.class_id}`"
                            class="text-gray-500 hover:underline text-sm font-medium">
                            Cancel
                        </Link>

                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 disabled:opacity-50 shadow-lg shadow-indigo-200 dark:shadow-none transition">
                            {{ form.processing ? 'Updating...' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
