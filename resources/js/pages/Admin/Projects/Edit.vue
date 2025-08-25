<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Edit Project</h1>
                <p class="text-gray-600">Update project information and details</p>
            </div>

            <div class="max-w-4xl">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Basic Information Form -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
                        <form @submit.prevent="submitBasicInfo" class="space-y-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Project Name -->
                                <div class="mb-6">
                                    <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Project Name *
                                    </label>
                                    <input id="project_name" v-model="basicForm.project_name" type="text" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />
                                    <p v-if="basicForm.errors.project_name" class="mt-1 text-sm text-red-600">
                                        {{ basicForm.errors.project_name }}
                                    </p>
                                </div>

                                <!-- Short Description -->
                                <div class="mb-6">
                                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Short Description
                                    </label>
                                    <textarea id="short_description" v-model="basicForm.short_description" rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                                    <p v-if="basicForm.errors.short_description" class="mt-1 text-sm text-red-600">
                                        {{ basicForm.errors.short_description }}
                                    </p>
                                </div>

                                <!-- Parent Project -->
                                <div class="mb-6">
                                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Parent Project
                                    </label>
                                    <select id="parent_id" v-model="basicForm.parent_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">-- No Parent (Top Level Project) --</option>
                                        <option v-for="parent in parentProjects" :key="parent.id" :value="parent.id">
                                            {{ parent.project_name }}
                                        </option>
                                    </select>
                                    <p v-if="basicForm.errors.parent_id" class="mt-1 text-sm text-red-600">
                                        {{ basicForm.errors.parent_id }}
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="mb-6">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status
                                    </label>
                                    <select id="status" v-model="basicForm.status"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    <p v-if="basicForm.errors.status" class="mt-1 text-sm text-red-600">
                                        {{ basicForm.errors.status }}
                                    </p>
                                </div>

                                <!-- Icon Upload -->
                                <div class="mb-6">
                                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                                        Project Icon
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img v-if="iconPreview || project.icon"
                                                :src="iconPreview || `/storage/${project.icon}`" alt="Icon preview"
                                                class="h-16 w-16 object-contain rounded-lg border" />
                                            <div v-else
                                                class="h-16 w-16 bg-gray-100 rounded-lg border flex items-center justify-center">
                                                <ImageIcon width="24" height="24" class="text-gray-400" />
                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <input id="icon" type="file" ref="iconInput" @change="handleIconChange"
                                                accept="image/*"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100" />
                                            <p class="mt-1 text-sm text-gray-500">
                                                PNG, JPG, GIF up to 2MB
                                            </p>
                                        </div>
                                    </div>
                                    <p v-if="basicForm.errors.icon" class="mt-1 text-sm text-red-600">
                                        {{ basicForm.errors.icon }}
                                    </p>
                                </div>

                                <!-- Sort Order -->
                                <div class="mb-6">
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sort Order
                                    </label>
                                    <input id="sort_order" v-model.number="basicForm.sort_order" type="number" min="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />
                                    <p v-if="basicForm.errors.sort_order" class="mt-1 text-sm text-red-600">
                                        {{ basicForm.errors.sort_order }}
                                    </p>
                                </div>
                            </div>

                            <!-- Basic Form Actions -->
                            <div class="flex items-center justify-end space-x-3">
                                <Link :href="route('admin.projects.index')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                                </Link>
                                <button type="submit" :disabled="basicForm.processing"
                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 disabled:opacity-50">
                                    <span v-if="basicForm.processing">Updating...</span>
                                    <span v-else>Update Project</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Project Details Form -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Project Details</h2>
                        <form @submit.prevent="submitDetails" class="space-y-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Detail Title -->
                                <div class="mb-6">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Detail Title *
                                    </label>
                                    <input id="title" v-model="detailsForm.title" type="text" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        placeholder="Main title for project details page" />
                                    <p v-if="detailsForm.errors.title" class="mt-1 text-sm text-red-600">
                                        {{ detailsForm.errors.title }}
                                    </p>
                                </div>

                                <!-- Content -->
                                <div class="mb-6">
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                        Content *
                                    </label>
                                    <textarea id="content" v-model="detailsForm.content" rows="12" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        placeholder="Detailed project description (HTML supported)"></textarea>
                                    <p class="mt-1 text-sm text-gray-500">
                                        You can use HTML tags for formatting
                                    </p>
                                    <p v-if="detailsForm.errors.content" class="mt-1 text-sm text-red-600">
                                        {{ detailsForm.errors.content }}
                                    </p>
                                </div>
                            </div>

                            <!-- Details Form Actions -->
                            <div class="flex items-center justify-end">
                                <button type="submit" :disabled="detailsForm.processing"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 disabled:opacity-50">
                                    <span v-if="detailsForm.processing">Saving...</span>
                                    <span v-else>Save Details</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ImageIcon } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    project: Object,
    parentProjects: Array
})

const iconInput = ref(null)
const iconPreview = ref(null)

// Basic information form
const basicForm = useForm({
    project_name: props.project.project_name,
    short_description: props.project.short_description || '',
    parent_id: props.project.parent_id || '',
    icon: null,
    sort_order: props.project.sort_order || 0,
    status: props.project.status || 'active'
})

// Project details form
const detailsForm = useForm({
    title: props.project.detail?.title || props.project.project_name,
    content: props.project.detail?.content || ''
})

function handleIconChange(event) {
    const file = event.target.files[0]
    if (file) {
        basicForm.icon = file

        // Create preview
        const reader = new FileReader()
        reader.onload = (e) => {
            iconPreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    } else {
        basicForm.icon = null
        iconPreview.value = null
    }
}

function submitBasicInfo() {
    basicForm.post(route('admin.projects.update', props.project.id), {
        _method: 'put',
        preserveScroll: true
    })
}

function submitDetails() {
    detailsForm.post(route('admin.projects.update-details', props.project.id), {
        preserveScroll: true
    })
}
</script>