<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

// Identify role using Spatie's shared data or the auth object
const page = usePage();
const user = computed(() => page.props.auth.user);

// Check if the user has the 'lecturer' role
const isLecturer = computed(() => user.value.roles?.some(r => r.name === 'lecturer') || false);
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-7xl mx-auto space-y-6">

            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border dark:border-gray-700 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome, {{ user.name }}</h1>
                    <p class="text-sm text-gray-500">
                        <!-- {{ isLecturer ? 'Manage your CS modules and track student performance.' : 'Check your grades and
                        upcoming computer science exams.' }} -->
                    </p>
                </div>
                <Link :href="isLecturer ? '/exams/create' : '/student/exams'"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition text-center">
                    {{ isLecturer ? 'Create New Exam' : 'View My Exams' }}
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="p-6 bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 shadow-sm text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                        {{ isLecturer ? 'Total Exams' : 'Exams Completed' }}
                    </p>
                    <p class="text-3xl font-black mt-2 text-gray-800 dark:text-gray-100">12</p>
                </div>

                <div
                    class="p-6 bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 shadow-sm text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                        {{ isLecturer ? 'Assigned Classes' : 'Current Average' }}
                    </p>
                    <p class="text-3xl font-black mt-2 text-gray-800 dark:text-gray-100">{{ isLecturer ? '04' : '85%' }}
                    </p>
                </div>

                <div
                    class="p-6 bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 shadow-sm text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                        {{ isLecturer ? 'Pending Submissions' : 'Rank' }}
                    </p>
                    <p class="text-3xl font-black mt-2 text-gray-800 dark:text-gray-100">{{ isLecturer ? '28' : '#05' }}
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl border dark:border-gray-700 shadow-sm">
                <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100">Recent Computer Science Activity</h3>
                    <Link :href="isLecturer ? '/exams' : '/student/exams'"
                        class="text-xs font-bold text-indigo-600 hover:underline">View All</Link>
                </div>

                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-400 text-[10px] uppercase font-bold">
                            <tr>
                                <th class="px-6 py-3">Subject / Exam</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            <tr v-for="n in 3" :key="n" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800 dark:text-gray-200">Data Structures & Algorithms
                                    </p>
                                    <p class="text-xs text-gray-500">CS-202 • Midterm Quiz</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-[10px] font-bold rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        {{ isLecturer ? 'Active' : 'Completed' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link :href="isLecturer ? '/exams/1/preview' : '/student/results/1'"
                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                        {{ isLecturer ? 'Manage' : 'View Results' }}
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
