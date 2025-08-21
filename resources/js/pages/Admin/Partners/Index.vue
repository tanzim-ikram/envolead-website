<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Trash2, Plus, Building, Users } from 'lucide-vue-next';

interface Partner {
    id: number;
    partner_name: string;
    type: 'clubs' | 'company';
    logo: string;
    logo_url: string;
}

const props = defineProps<{
    partners: Partner[];
}>();

const destroyPartner = (id: number, name: string) => {
    if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
        router.delete(route('admin.partners.destroy', id));
    }
};

const getTypeIcon = (type: string) => {
    return type === 'company' ? Building : Users;
};

const getTypeColor = (type: string) => {
    return type === 'company'
        ? 'bg-blue-100 text-blue-800'
        : 'bg-purple-100 text-purple-800';
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Partners', href: '/admin/partners' },
];
</script>

<template>

    <Head title="Manage Partners" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Manage Partners</h1>
                    <p class="text-gray-600 mt-1">Add and manage your organization's partners</p>
                </div>
                <Link :href="route('admin.partners.create')"
                    class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-md shadow-sm transition-colors duration-200">
                <Plus class="w-4 h-4 mr-2" />
                Add Partner
                </Link>
            </div>

            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div v-if="partners.length === 0" class="text-center py-12">
                    <Users class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No partners yet</h3>
                    <p class="text-gray-500 mb-4">Get started by adding your first partner.</p>
                    <Link :href="route('admin.partners.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-md">
                    <Plus class="w-4 h-4 mr-2" />
                    Add Partner
                    </Link>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Logo
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Partner Name
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="partner in partners" :key="partner.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                        <img v-if="partner.logo_url" :src="partner.logo_url" :alt="partner.partner_name"
                                            class="w-full h-full object-contain" />
                                        <component v-else :is="getTypeIcon(partner.type)"
                                            class="w-6 h-6 text-gray-400" />
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ partner.partner_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="getTypeColor(partner.type)"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize">
                                        <component :is="getTypeIcon(partner.type)" class="w-3 h-3 mr-1" />
                                        {{ partner.type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <Link :href="route('admin.partners.edit', partner.id)"
                                            class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200">
                                        <Pencil class="w-4 h-4" />
                                        </Link>
                                        <button @click="destroyPartner(partner.id, partner.partner_name)"
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