<script setup>
// eslint-disable-next-line vue/script-setup-uses-vars
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ member: Object });

const previewUrl = ref(props.member.photo);

const form = useForm({
    name: props.member.name || '',
    designation: props.member.designation || '',
    email: props.member.email || '',
    linkedin: props.member.linkedin || '',
    bio: props.member.bio || '',
    photo: null,
});

const submit = () => {
    const changedData = {};

    if (form.name !== props.member.name) changedData.name = form.name;
    if (form.designation !== props.member.designation) changedData.designation = form.designation;
    if (form.email !== props.member.email) changedData.email = form.email;
    if (form.linkedin !== props.member.linkedin) changedData.linkedin = form.linkedin;
    if (form.bio !== props.member.bio) changedData.bio = form.bio;
    if (form.photo) changedData.photo = form.photo;

    // Update form with only changed data
    Object.keys(changedData).forEach(key => {
        form[key] = changedData[key];
    });

    // Submit as POST but spoof method to PUT
    form.post(route('admin.team.update', props.member.id), {
        method: 'put', // 👈 VERY IMPORTANT
        forceFormData: true,
        preserveScroll: true,
    });
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const breadcrumbs = computed(() => [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Team', href: '/admin/team' },
]);
</script>

<template>
    <Head title="Edit Member" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 mb-1">Edit Team Member</h1>
                <p class="text-gray-600">Update member information</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">{{ props.member.name }}</h2>
                    <p class="text-gray-600 text-sm">{{ props.member.designation || 'Team Member' }}</p>
                </div>

                <form @submit.prevent="submit" class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none"
                                    />
                                    <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                    <input
                                        v-model="form.designation"
                                        type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none"
                                    />
                                    <div v-if="form.errors.designation" class="text-sm text-red-600 mt-1">{{ form.errors.designation }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none"
                                    />
                                    <div v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                                    <input
                                        v-model="form.linkedin"
                                        type="url"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none"
                                    />
                                    <div v-if="form.errors.linkedin" class="text-sm text-red-600 mt-1">{{ form.errors.linkedin }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Biography</label>
                                <textarea
                                    v-model="form.bio"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none resize-none"
                                ></textarea>
                                <div v-if="form.errors.bio" class="text-sm text-red-600 mt-1">{{ form.errors.bio }}</div>
                            </div>
                        </div>

                        <!-- Right Column - Photo -->
                        <div class="lg:col-span-1">
                            <div class="space-y-4">
                                <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                                <div class="flex justify-center">
                                    <div class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        <img v-if="previewUrl" :src="previewUrl" :alt="props.member.name" class="w-full h-full object-cover" />
                                        <div v-else class="w-full h-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <input type="file" @change="handleFileChange" accept="image/*" class="hidden" id="photo-upload" />
                                    <label for="photo-upload"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Upload Photo
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG up to 10MB</p>
                                    <div v-if="form.errors.photo" class="text-sm text-red-600 mt-2">{{ form.errors.photo }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 mt-8 border-t border-gray-200 flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="$inertia.visit(route('admin.team.manage'))"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 font-medium hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            :class="[
                                'px-6 py-2 rounded-md font-medium text-white',
                                form.processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-700 hover:bg-green-800'
                            ]"
                        >
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update Member</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
