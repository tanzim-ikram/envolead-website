<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
defineProps({ members: Array });

const deleteMember = (id) => {
    if (confirm('Are you sure you want to delete this member?')) {
        router.delete(route('admin.team.destroy', id));
    }
};
</script>

<template>

    <Head title="Manage Team Members" />
    <AppLayout>
        <div class="p-4">
            <h1 class="text-2xl font-bold mb-6">Update Member Info</h1>
            <table class="w-full table-auto border text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Photo</th>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Designation</th>
                        <th class="p-2 border">Email</th>
                        <th class="p-2 border text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="member in members" :key="member.id" class="hover:bg-gray-50">
                        <td class="p-2 border"><img :src="member.photo" class="h-12 w-12 object-cover rounded" /></td>
                        <td class="p-2 border">{{ member.name }}</td>
                        <td class="p-2 border">{{ member.designation }}</td>
                        <td class="p-2 border">{{ member.email || '—' }}</td>
                        <td class="p-2 border text-center space-x-2">
                            <Link :href="route('admin.team.edit', member.id)" class="text-blue-600 hover:underline">
                            Update</Link>
                            <button @click="deleteMember(member.id)"
                                class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
