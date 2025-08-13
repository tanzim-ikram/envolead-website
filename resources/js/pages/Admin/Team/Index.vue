<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    members: Array
});

const breadcrumbs = computed(() => [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Team', href: '/admin/team' },
]);
</script>

<template>
    <Head title="Team Members" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header Section -->
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 mb-1">All Team Members</h1>
                <p class="text-gray-600">View details of all team members.</p>
            </div>

            <!-- Card Container -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-800">Team Directory</h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border-t border-gray-200">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-center">Photo</th>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Designation</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">LinkedIn</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="member in members"
                                :key="member.id"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="px-6 py-4 text-center">
                                    <img
                                        :src="member.photo"
                                        alt="Member Photo"
                                        class="h-12 w-12 rounded-full object-cover mx-auto border border-gray-300"
                                    />
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ member.name }}</td>
                                <td class="px-6 py-4 text-gray-700">
                                    <div v-for="(title, idx) in member.designation.split(',')" :key="idx">
                                        {{ title.trim() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ member.email || '—' }}
                                </td>
                                <td class="px-6 py-4 text-green-700 underline">
                                    <a
                                        v-if="member.linkedin"
                                        :href="member.linkedin"
                                        target="_blank"
                                        class="hover:text-green-800"
                                    >
                                        LinkedIn
                                    </a>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <Link
                                        :href="`/team/${member.slug}`"
                                        class="text-green-700 font-medium hover:underline"
                                    >
                                        View
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
