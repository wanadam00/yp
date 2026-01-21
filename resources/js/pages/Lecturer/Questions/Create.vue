<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
    exams: {
        type: Array,
        required: true
    }
})

const form = useForm({
    exam_id: '',
    question_text: '',
    question_type: 'tq',
    marks: 1,
    options: [
        { text: '', is_correct: false },
        { text: '', is_correct: false },
        { text: '', is_correct: false },
        { text: '', is_correct: false }
    ]
})

const isMcq = computed(() => form.question_type === 'mcq')

const addOption = () => {
    form.options.push({ text: '', is_correct: false })
}

const removeOption = (index) => {
    if (form.options.length > 2) {
        form.options.splice(index, 1)
    }
}

const submit = () => {
    form.post('/questions', {
        onSuccess: () => {
            form.reset()
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Create Question</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Exam -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Exam</label>
                    <select v-model="form.exam_id" class="w-full p-2 border rounded" required>
                        <option value="" disabled>Select an exam</option>
                        <option v-for="exam in exams" :key="exam.id" :value="exam.id">{{ exam.title }} - {{
                            exam.subject?.subject_name ?? 'No Subject' }}
                        </option>
                    </select>
                    <span class="text-red-500 text-sm" v-if="form.errors.exam_id">{{ form.errors.exam_id }}</span>
                </div>

                <!-- Question Text -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Question Text</label>
                    <textarea v-model="form.question_text" class="w-full p-2 border rounded" rows="4"
                        required></textarea>
                    <span class="text-red-500 text-sm" v-if="form.errors.question_text">{{ form.errors.question_text
                        }}</span>
                </div>

                <!-- Question Type -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Question Type</label>
                    <select v-model="form.question_type" class="w-full p-2 border rounded" required>
                        <option value="tq">Text Answer</option>
                        <option value="mcq">Multiple Choice</option>
                    </select>
                    <span class="text-red-500 text-sm" v-if="form.errors.question_type">{{ form.errors.question_type
                        }}</span>
                </div>

                <!-- Marks -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Marks</label>
                    <input v-model.number="form.marks" type="number" min="1" class="w-full p-2 border rounded"
                        required />
                    <span class="text-red-500 text-sm" v-if="form.errors.marks">{{ form.errors.marks }}</span>
                </div>

                <!-- Options for MCQ -->
                <div v-if="isMcq" class="space-y-2">
                    <label class="block text-gray-700 dark:text-gray-300">Options</label>
                    <div v-for="(option, index) in form.options" :key="index" class="flex items-center space-x-2">
                        <input v-model="option.text" type="text" placeholder="Option text"
                            class="flex-1 p-2 border rounded" required />
                        <input v-model="option.is_correct" type="checkbox" class="w-4 h-4" />
                        <label class="text-sm text-gray-600 dark:text-gray-400">Correct</label>
                        <button type="button" @click="removeOption(index)" class="text-red-500 hover:text-red-700"
                            v-if="form.options.length > 2">-</button>
                    </div>
                    <button type="button" @click="addOption" class="text-blue-500 hover:text-blue-700">+ Add
                        Option</button>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <Link href="/questions"
                        class="px-4 py-2 text-gray-700 border rounded hover:bg-gray-100 dark:hover:bg-gray-700">Cancel
                    </Link>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Create</button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
