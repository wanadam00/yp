<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    exam: Object,
    attempt: Object,
    initialRemainingSeconds: Number // Add this
})

// Timer state
const remainingTime = ref(props.initialRemainingSeconds)
let timerInterval = null

const form = useForm({
    answers: {}
})

// Format seconds to MM:SS or HH:MM:SS
const timeLeftFormatted = computed(() => {
    // Math.floor removes the decimals
    const hours = Math.floor(remainingTime.value / 3600)
    const minutes = Math.floor((remainingTime.value % 3600) / 60)
    const seconds = Math.floor(remainingTime.value % 60)

    // If there is at least 1 hour, show HH:MM:SS
    if (hours > 0) {
        return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
    }

    // Otherwise show MM:SS
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
})

const submitExam = (isAutoSubmit = false) => {
    const message = isAutoSubmit
        ? 'Time is up! Your exam is being submitted automatically.'
        : 'Are you sure you want to submit your exam?';

    if (isAutoSubmit || confirm(message)) {
        form.post(`/student/exam-attempts/${props.attempt.id}/submit`)
    }
}

onMounted(() => {
    timerInterval = setInterval(() => {
        if (remainingTime.value > 0) {
            remainingTime.value--
        } else {
            clearInterval(timerInterval)
            submitExam(true) // Auto-submit when time hits 0
        }
    }, 1000)
})

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval)
})
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-3xl mx-auto">
            <div
                class="flex items-center justify-between mb-8 sticky top-0 bg-gray-50/90 backdrop-blur-md dark:bg-gray-900/90 py-4 z-10 border-b dark:border-gray-700">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ exam.title }}</h1>
                    <div class="flex items-center mt-1">
                        <span class="text-sm font-medium mr-2"
                            :class="remainingTime < 60 ? 'text-red-500 animate-pulse' : 'text-gray-500'">
                            Time Remaining:
                        </span>
                        <span class="font-mono text-lg font-bold" :class="{
                            'text-red-500 animate-pulse': remainingTime < 60,
                            'text-blue-600 dark:text-blue-400': remainingTime >= 60
                        }">
                            {{ timeLeftFormatted }}
                        </span>
                    </div>
                </div>

                <button @click="submitExam(false)"
                    class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 shadow-md transition">
                    Submit Exam
                </button>
            </div>

            <div class="space-y-8">
                <div v-for="(question, index) in exam.questions" :key="question.id"
                    class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                    <div class="flex items-center mb-4">
                        <span
                            class="px-3 py-1 text-xs font-bold text-blue-700 uppercase bg-blue-100 rounded-full dark:bg-blue-900/30 dark:text-blue-400">
                            Question {{ index + 1 }}
                        </span>
                        <span class="ml-auto text-sm text-gray-500">{{ question.marks }} Marks</span>
                    </div>

                    <p class="mb-6 text-xl font-medium text-gray-800 dark:text-gray-100">
                        {{ question.question_text }}
                    </p>

                    <div v-if="question.question_type === 'mcq'" class="space-y-3">
                        <label v-for="(option, optIndex) in question.options" :key="option.id"
                            class="flex items-center p-4 border rounded-lg cursor-pointer transition-all hover:bg-gray-50 dark:hover:bg-gray-700"
                            :class="form.answers[question.id] == option.id
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 ring-1 ring-blue-500'
                                : 'border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40'">

                            <input type="radio" :name="'question-' + question.id" :value="option.id"
                                v-model="form.answers[question.id]" class="hidden" />

                            <span
                                class="flex items-center justify-center w-8 h-8 mr-3 text-sm font-bold border rounded-full shadow-sm bg-white dark:bg-gray-800"
                                :class="form.answers[question.id] == option.id ? 'text-blue-600 border-blue-500' : 'text-gray-500 dark:border-gray-600'">
                                {{ String.fromCharCode(65 + optIndex) }}
                            </span>

                            <span class="text-gray-700 dark:text-gray-300">{{ option.option_text }}</span>
                        </label>
                    </div>

                    <div v-else-if="question.question_type === 'tq'" class="space-y-3">
                        <textarea v-model="form.answers[question.id]" rows="5" placeholder="Type your answer here..."
                            class="w-full p-4 border rounded-lg bg-gray-50 dark:bg-gray-900/40 border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></textarea>
                        <p class="text-xs text-gray-400 italic">This answer will be manually graded by the lecturer.</p>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
