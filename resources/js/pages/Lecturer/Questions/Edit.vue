<script setup>
import { computed, watch } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    exams: {
        type: Array,
        required: true
    },
    question: {
        type: Object,
        required: true
    }
})

const form = useForm({
    exam_id: props.question.exam_id,
    question_text: props.question.question_text,
    question_type: props.question.question_type,
    marks: props.question.marks,

    // IMPORTANT: Mapping must include the ID
    options: props.question.options?.map(option => ({
        id: option.id,           // 👈 CRITICAL: Laravel needs this to know which row to update
        text: option.option_text,
        is_correct: Boolean(option.is_correct),
    })) ?? []
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

watch(
    () => form.question_type,
    (type) => {
        if (type === 'tq') {
            form.options = [] // prevent accidental submit
        }
    }
)

const submit = () => {
    const payload = {
        exam_id: form.exam_id,
        question_text: form.question_text,
        question_type: form.question_type,
        marks: form.marks,
    }

    // ONLY send options if MCQ
    if (form.question_type === 'mcq') {
        payload.options = form.options
    }

    form.put(`/questions/${props.question.id}`, {
        data: payload,
        onSuccess: () => {
            // optional success toast
        }
    })
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">
                Edit Question
            </h1>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Exam -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Exam</label>
                    <select v-model="form.exam_id" class="w-full p-2 border rounded" required>
                        <option value="" disabled>Select an exam</option>
                        <option v-for="exam in exams" :key="exam.id" :value="exam.id">
                            {{ exam.title }} -
                            {{ exam.subject?.subject_name ?? 'No Subject' }}
                        </option>
                    </select>
                    <span v-if="form.errors.exam_id" class="text-red-500 text-sm">
                        {{ form.errors.exam_id }}
                    </span>
                </div>

                <!-- Question Text -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Question Text</label>
                    <textarea v-model="form.question_text" class="w-full p-2 border rounded" rows="4"
                        required></textarea>
                    <span v-if="form.errors.question_text" class="text-red-500 text-sm">
                        {{ form.errors.question_text }}
                    </span>
                </div>

                <!-- Question Type -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Question Type</label>
                    <select v-model="form.question_type" class="w-full p-2 border rounded" required>
                        <option value="tq">Text Answer</option>
                        <option value="mcq">Multiple Choice</option>
                    </select>
                    <span v-if="form.errors.question_type" class="text-red-500 text-sm">
                        {{ form.errors.question_type }}
                    </span>
                </div>

                <!-- Marks -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Marks</label>
                    <input v-model.number="form.marks" type="number" min="1" class="w-full p-2 border rounded"
                        required />
                    <span v-if="form.errors.marks" class="text-red-500 text-sm">
                        {{ form.errors.marks }}
                    </span>
                </div>

                <!-- MCQ Options -->
                <div v-if="isMcq" class="space-y-2">
                    <label class="block text-gray-700 dark:text-gray-300">Options</label>

                    <div v-for="(option, index) in form.options" :key="index" class="flex items-center space-x-2">
                        <input v-model="option.text" type="text" class="flex-1 p-2 border rounded" required />
                        <input v-model="option.is_correct" type="checkbox" class="w-4 h-4" />
                        <span class="text-sm">Correct</span>
                        <button type="button" @click="removeOption(index)" v-if="form.options.length > 2"
                            class="text-red-500">
                            −
                        </button>
                    </div>

                    <button type="button" @click="addOption" class="text-blue-500">
                        + Add Option
                    </button>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <Link href="/questions" class="px-4 py-2 border rounded">
                        Cancel
                    </Link>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </AppLayout>
</template>
