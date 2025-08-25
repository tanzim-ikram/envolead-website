<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem, } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, UsersRound, UserPlus, UserRoundPen, Newspaper, NotebookPen, FileCog, Handshake, CirclePlus, SquarePen } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Projects',
        href: '/admin/projects',
        icon: Handshake,
        items: [
            { title: 'Add new project', href: '/admin/projects/create', icon: CirclePlus },
            { title: 'Manage projects', href: '/admin/projects', icon: SquarePen },
        ],
    },
    {
        title: 'Team',
        href: '/team',
        icon: UsersRound,
        items: [
            { title: 'Add new member', href: '/admin/team/create', icon: UserPlus },
            { title: 'Update member info', href: '/admin/team/manage', icon: UserRoundPen },
            { title: 'View all members', href: '/admin/team', icon: UsersRound },
        ],
    },
    {
        title: 'Partners',
        href: '/admin/partners',
        icon: Handshake,
        items: [
            { title: 'Add new partner', href: '/admin/partners/create', icon: CirclePlus },
            { title: 'Manage partners', href: '/admin/partners', icon: SquarePen },
        ],
    },
    {
        title: 'News',
        href: '/news',
        icon: Newspaper,
        items: [
            { title: 'Post News', href: '/admin/news/create', icon: NotebookPen },
            { title: 'Manage News', href: '/admin/news', icon: FileCog },
        ],
    },
];

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
        <SidebarHeader class="mb-2">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
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
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
