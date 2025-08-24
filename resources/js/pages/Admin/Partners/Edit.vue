<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Upload, Users } from 'lucide-vue-next';

const props = defineProps<{ partner: any }>();

const form = useForm({
    partner_name: props.partner.partner_name,
    type: props.partner.type as 'clubs' | 'company',
    logo: null as File | null,
});

const previewUrl = ref<string | null>(props.partner.logo_url || null);

const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.logo = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.put(route('admin.partners.update', props.partner.id), {
        forceFormData: true,
    });
};

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Partners', href: '/admin/partners' },
    { title: 'Edit Partner', href: `/admin/partners/${props.partner.id}/edit` },
];
</script>

<template>

    <Head title="Edit Partner" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Partner</h1>
                    <p class="text-gray-600 mt-1">Update partner details and logo</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-sm border-2 border-gray-200 p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Partner Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Partner Name</label>
                        <input v-model="form.partner_name" type="text"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none" />
                        <div v-if="form.errors.partner_name" class="text-sm text-red-600 mt-1">
                            {{ form.errors.partner_name }}
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select v-model="form.type" class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none">
                            <option value="clubs">Clubs</option>
                            <option value="company">Company</option>
                        </select>
                        <div v-if="form.errors.type" class="text-sm text-red-600 mt-1">
                            {{ form.errors.type }}
                        </div>
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-24 h-24 border-2 border-gray-300 rounded-md overflow-hidden bg-gray-50 flex items-center justify-center">
                                <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover" />
                                <Users v-else class="w-6 h-6 text-gray-400" />
                            </div>
                            <div>
                                <input type="file" @change="handleFileChange" accept="image/*" class="hidden"
                                    id="logo-upload" />
                                <label for="logo-upload"
                                    class="cursor-pointer px-4 py-2 border-2 border-gray-300 rounded-md bg-white hover:bg-gray-50 inline-flex items-center">
                                    <Upload class="w-4 h-4 mr-2" /> Change Logo
                                </label>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                                <div v-if="form.errors.logo" class="text-sm text-red-600 mt-1">
                                    {{ form.errors.logo }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="$inertia.visit(route('admin.partners.index'))"
                            class="px-6 py-2 border-2 border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 rounded-md font-medium text-white bg-green-700 hover:bg-green-800">
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update Partner</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
