<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    exam: Object,
    assignedClasses: Array, // Passed from controller
    selectedClassId: String,
})

const filterByClass = (id) => {
    router.get(`/exams/${props.exam.id}/preview`, { class_id: id }, { preserveState: true });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-4xl mx-auto">
            <div v-if="assignedClasses.length > 0" class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Class View:</label>
                <select @change="filterByClass($event.target.value)" :value="selectedClassId"
                    class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700">
                    <option value="">General Preview</option>
                    <option v-for="cls in assignedClasses" :key="cls.id" :value="cls.id">
                        {{ cls.class_name }} </option>
                </select>
            </div>

            <div v-if="selectedClassId" class="...">
                <p class="text-sm">
                    Viewing preview for <strong>{{assignedClasses.find(c => c.id == selectedClassId)?.class_name
                        }}</strong>.
                </p>
            </div>

            <div class="space-y-6 mt-8">
                <div v-for="(question, index) in exam.questions" :key="question.id"
                    class="p-6 bg-white border rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">

                    <div class="flex justify-between mb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase">Question {{ index + 1 }}</span>
                        <span class="text-xs font-semibold text-blue-600">{{ question.marks }} Marks</span>
                    </div>

                    <p class="text-lg text-gray-800 dark:text-gray-100 mb-4">{{ question.question_text }}</p>

                    <div class="grid gap-2">
                        <div v-for="option in question.options" :key="option.id"
                            class="p-3 border rounded-lg flex items-center"
                            :class="option.is_correct ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700'">

                            <div class="w-5 h-5 rounded-full border mr-3 flex items-center justify-center"
                                :class="option.is_correct ? 'bg-green-500 border-green-500 text-white' : 'bg-white'">
                                <span v-if="option.is_correct" class="text-[10px]">✓</span>
                            </div>

                            <span
                                :class="option.is_correct ? 'text-green-800 dark:text-green-300 font-medium' : 'text-gray-600 dark:text-gray-400'">
                                {{ option.option_text }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
