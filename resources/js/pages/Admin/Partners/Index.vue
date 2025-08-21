<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
defineProps<{ partners: any }>();
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Manage Partners</h1>
        <Link :href="route('admin.partners.create')" class="bg-blue-500 text-white px-3 py-2 rounded">+ Add Partner
        </Link>

        <table class="min-w-full mt-4 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Name</th>
                    <th class="p-2">Type</th>
                    <th class="p-2">Logo</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="partner in partners.data" :key="partner.id">
                    <td class="p-2">{{ partner.partner_name }}</td>
                    <td class="p-2">{{ partner.type }}</td>
                    <td class="p-2">
                        <img v-if="partner.logo" :src="`/storage/${partner.logo}`" class="h-10" />
                    </td>
                    <td class="p-2">
                        <Link :href="route('admin.partners.edit', partner.id)" class="text-blue-600 mr-2">Edit</Link>
                        <Link as="button" method="delete" :href="route('admin.partners.destroy', partner.id)"
                            class="text-red-600">Delete</Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
