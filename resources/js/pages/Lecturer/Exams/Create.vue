<script setup>
import { ref } from 'vue'
import { watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
    subjects: {
        type: Array,
        required: true
    }
})

const form = useForm({
    title: '',
    description: '',
    subject_id: '',
    duration_minutes: 0,
    start_time: '',
    end_time: ''
})

watch(
    () => [form.start_time, form.end_time],
    ([start, end]) => {
        if (!start || !end) {
            form.duration_minutes = 0
            return
        }

        const startTime = new Date(start)
        const endTime = new Date(end)

        if (endTime <= startTime) {
            form.duration_minutes = 0
            return
        }

        // difference in minutes (NUMBER)
        const diffMinutes = Math.round(
            (endTime.getTime() - startTime.getTime()) / 60000
        )

        form.duration_minutes = diffMinutes
    }
)

const submit = () => {
    form.post('/exams', {
        onSuccess: () => {
            form.reset()
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Create Exam</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Title</label>
                    <input v-model="form.title" type="text" class="w-full p-2 border rounded" required />
                    <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title }}</span>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Description</label>
                    <textarea v-model="form.description" class="w-full p-2 border rounded"></textarea>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Subject</label>
                    <select v-model="form.subject_id" class="w-full p-2 border rounded" required>
                        <option value="" disabled>Select a subject</option>
                        <option v-for="sub in subjects" :key="sub.id" :value="sub.id">{{ sub.subject_name }}</option>
                    </select>
                    <span class="text-red-500 text-sm" v-if="form.errors.subject_id">{{ form.errors.subject_id }}</span>
                </div>

                <!-- Start & End Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">Start Time</label>
                        <input v-model="form.start_time" type="datetime-local" class="w-full p-2 border rounded" />
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">End Time</label>
                        <input v-model="form.end_time" type="datetime-local" class="w-full p-2 border rounded" />
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">
                        Duration (minutes)
                    </label>

                    <input v-model.number="form.duration_minutes" type="number" min="1" readonly
                        class="w-full p-2 border rounded bg-gray-100 cursor-not-allowed" />

                    <!-- <span v-if="form.duration_minutes > 0" class="text-sm text-gray-500">
                        {{ form.duration_minutes }} minutes
                    </span> -->
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <Link href="/exams"
                        class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel
                    </Link>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Create</button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
