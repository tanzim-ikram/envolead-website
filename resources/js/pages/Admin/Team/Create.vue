<script setup>
// eslint-disable-next-line vue/script-setup-uses-vars
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Upload } from 'lucide-vue-next';

const form = useForm({
    name: '',
    designation: '',
    other_designations: '',
    email: '',
    linkedin: '',
    bio: '',
    photo: null,
});

const previewUrl = ref(null);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('admin.team.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};

const breadcrumbs = computed(() => [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Team', href: '/admin/team' },
]);
</script>

<template>

    <Head title="Add Member" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 mb-1">Add Team Member</h1>
                <p class="text-gray-600">Enter new member information</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <form @submit.prevent="submit" class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                    <input v-model="form.name" type="text"
                                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none" />
                                    <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name
                                        }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                    <input v-model="form.designation" type="text"
                                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none" />
                                    <div v-if="form.errors.designation" class="text-sm text-red-600 mt-1">{{
                                        form.errors.designation }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Other Designations
                                    (Optional)</label>
                                <input v-model="form.other_designations" type="text"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none" />
                                <div v-if="form.errors.other_designations" class="text-sm text-red-600 mt-1">
                                    {{ form.errors.other_designations }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input v-model="form.email" type="email"
                                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none" />
                                    <div v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email
                                        }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                                    <input v-model="form.linkedin" type="url"
                                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none" />
                                    <div v-if="form.errors.linkedin" class="text-sm text-red-600 mt-1">{{
                                        form.errors.linkedin }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Biography</label>
                                <textarea v-model="form.bio" rows="4"
                                    class="w-full px-4 py-2 border-2 border-gray-300 rounded-md focus:border-green-500 focus:outline-none resize-none"></textarea>
                                <div v-if="form.errors.bio" class="text-sm text-red-600 mt-1">{{ form.errors.bio }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Photo -->
                        <div class="lg:col-span-1">
                            <div class="space-y-4">
                                <label class="block text-sm font-medium text-gray-700 text-center">Profile Photo</label>
                                <div class="flex justify-center">
                                    <div
                                        class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200">
                                        <img v-if="previewUrl" :src="previewUrl" alt="Preview"
                                            class="w-full h-full object-cover" />
                                        <div v-else class="w-full h-full flex items-center justify-center">
                                            <!-- Empty preview -->
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <input type="file" @change="handleFileChange" accept="image/*" class="hidden"
                                        id="photo-upload" />
                                    <label for="photo-upload"
                                        class="inline-flex items-center px-4 py-2 border-2 border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                        <Upload class="w-4 h-4 mr-2" />
                                        Upload Photo
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG up to 10MB</p>
                                    <div v-if="form.errors.photo" class="text-sm text-red-600 mt-2">{{ form.errors.photo
                                        }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 mt-8 border-t border-gray-200 flex justify-end space-x-4">
                        <button type="button" @click="$inertia.visit(route('admin.team.manage'))"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 font-medium hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" :disabled="form.processing" :class="[
                            'px-6 py-2 rounded-md font-medium text-white',
                            form.processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-700 hover:bg-green-800'
                        ]">
                            <span v-if="form.processing">Adding...</span>
                            <span v-else>Add Member</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
