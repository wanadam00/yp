<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <div
        class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC] font-sans selection:bg-indigo-500 selection:text-white">
        <header
            class="sticky top-0 z-50 w-full border-b border-[#19140015] bg-white/80 backdrop-blur-md dark:border-[#3E3E3A] dark:bg-[#0a0a0a]/80">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-4 lg:px-8">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white">
                        YP</div>
                    <span class="text-xl font-black tracking-tight">Yayasan Peneraju LMS</span>
                </div>

                <div class="flex items-center gap-4">
                    <Link v-if="$page.props.auth.user" :href="dashboard()"
                        class="rounded-full bg-indigo-600 px-6 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()" class="text-sm font-medium hover:text-indigo-600 transition">Log in</Link>
                        <Link v-if="canRegister" :href="register()"
                            class="rounded-full bg-[#1b1b18] px-6 py-2 text-sm font-bold text-white hover:bg-gray-800 dark:bg-[#EDEDEC] dark:text-[#1b1b18] dark:hover:bg-white transition">
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <main>
            <section class="relative px-6 pt-16 pb-24 lg:px-8 lg:pt-16 text-center">
                <div class="mx-auto max-w-3xl">
                    <div class="mb-8 flex justify-center">
                        <span
                            class="rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:border-indigo-900 dark:bg-indigo-900/30 dark:text-indigo-400">
                            v2.0 is now live for 2026
                        </span>
                    </div>
                    <h1 class="text-5xl font-black tracking-tight sm:text-7xl">
                        Streamlining <span class="text-indigo-600">CS Assessments</span> for everyone.
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                        The dedicated examination platform for Computer Science faculties. Secure MCQ testing, instant
                        grading,
                        and detailed performance analytics for students and lecturers.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <Link :href="register()"
                            class="rounded-xl bg-indigo-600 px-8 py-4 text-sm font-bold text-white shadow-lg hover:bg-indigo-700 transition">
                            Register Now
                        </Link>
                        <a href="#features" class="text-sm font-bold leading-6">Learn more <span
                                aria-hidden="true">→</span></a>
                    </div>
                </div>
            </section>
        </main>

        <footer
            class="fixed bottom-0 left-0 w-full z-50 border-t border-[#19140015] bg-white/80 backdrop-blur-md py-4 dark:border-[#3E3E3A] dark:bg-[#0a0a0a]/80">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-sm text-gray-500">
                    © 2026 Yayasan Peneraju Learning Management System. Built for Modern Education.
                </div>
                <div class="flex gap-6 text-sm font-medium text-gray-500">
                    <a href="#" class="hover:text-indigo-600">Privacy</a>
                    <a href="#" class="hover:text-indigo-600">Terms</a>
                    <a href="#" class="hover:text-indigo-600">Support</a>
                </div>
            </div>
        </footer>
    </div>
</template>
