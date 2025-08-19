<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { UserPlus } from 'lucide-vue-next';
import { computed } from 'vue';

defineProps({ members: Array });

const deleteMember = (id) => {
    if (confirm('Are you sure you want to delete this member?')) {
        router.delete(route('admin.team.destroy', id));
    }
};

const breadcrumbs = computed(() => [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Team', href: '/admin/team' },
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
                    class="inline-block px-5 py-2 text-sm font-medium bg-green-700 text-white rounded-md hover:bg-green-800 transition">
                <div>
                    <UserPlus class="mr-2 w-5 h-5 inline" />
                    Add New Member
                </div>
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
                                <th class="px-6 py-3 text-center">Action</th>
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
                                <td class="px-6 py-4 text-center space-x-2">
                                    <Link :href="route('admin.team.edit', member.id)"
                                        class="text-blue-600 font-medium hover:underline">
                                    Update
                                    </Link>
                                    <button @click="deleteMember(member.id)"
                                        class="text-red-600 font-medium hover:underline">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
