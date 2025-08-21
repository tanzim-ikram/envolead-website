<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { UserPlus, Pencil, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps({ members: Array });

const deleteMember = (id, name) => {
    if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
        router.delete(route('admin.team.destroy', id));
    }
};

const breadcrumbs = computed(() => [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Team', href: '/admin/team' },
    { title: 'Update Member Info', href: '/admin/team/manage' },
]);
</script>

<template>

    <Head title="Manage Team Members" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 mb-1">Update Member Info</h1>
                    <p class="text-gray-600">Edit or remove existing team members.</p>
                </div>
                <Link :href="route('admin.team.create')"
                    class="inline-flex items-center px-5 py-2 text-sm font-medium bg-green-700 text-white rounded-md hover:bg-green-800 transition-colors duration-200">
                <UserPlus class="mr-2 w-5 h-5" />
                Add New Member
                </Link>
            </div>

            <!-- Card Wrapper -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-800">Manage Team</h2>
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
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="member in members" :key="member.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center">
                                    <img :src="member.photo" alt="Member Photo"
                                        class="h-12 w-12 rounded-full object-cover mx-auto border border-gray-300" />
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ member.name }}</td>
                                <td class="px-6 py-4 text-gray-700">
                                    <div v-for="(title, idx) in member.designation.split(';')" :key="idx">
                                        {{ title.trim() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ member.email || '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <Link :href="route('admin.team.edit', member.id)"
                                            class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200">
                                        <Pencil class="w-4 h-4" />
                                        </Link>
                                        <button @click="deleteMember(member.id, member.name)"
                                            class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>