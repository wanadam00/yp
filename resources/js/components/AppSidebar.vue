<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, FileQuestionIcon, BookOpenText, FileBadge } from 'lucide-vue-next';

import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';

import AppLogo from './AppLogo.vue';

// 👉 Get roles from Inertia
const page = usePage()
const roles = computed<string[]>(() => page.props.auth?.roles ?? [])

// Helper
const hasRole = (role: string) => roles.value.includes(role)

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
    ]

    // Lecturer menu
    if (hasRole('lecturer')) {
        items.push(
            {
                title: 'Exams',
                href: '/exams',
                icon: BookOpenText,
            },
            {
                title: 'Grading',
                href: '/grading',
                icon: FileBadge,
            },
            {
                title: 'Questions',
                href: '/questions',
                icon: FileQuestionIcon,
            },
            {
                title: 'Classes',
                href: '/class-student',
                icon: Folder,
            }
        )
    }

    // Student menu
    if (hasRole('student')) {
        items.push({
            title: 'My Exams',
            href: '/student/exams',
            icon: BookOpen,
        })
    }

    return items
})

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
