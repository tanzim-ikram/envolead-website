<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <Link :href="route('admin.projects.index')" class="hover:text-gray-700">Projects</Link>
                    <span>/</span>
                    <span class="text-gray-900">Edit {{ project.project_name }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Project</h1>
                <p class="text-gray-600 mt-2">Update project information and details</p>
            </div>

            <!-- Success Messages -->
            <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <CheckCircle class="w-5 h-5 text-green-600 mr-2" />
                    <p class="text-green-800">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div class="max-w-6xl">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Basic Information Form -->
                    <div>
                        <div class="flex items-center mb-4">
                            <Settings class="w-5 h-5 text-gray-600 mr-2" />
                            <h2 class="text-xl font-semibold text-gray-900">Basic Information</h2>
                        </div>

                        <form @submit.prevent="submitBasicInfo" class="space-y-6">
                            <div class="bg-white rounded-lg shadow-sm border p-6">
                                <!-- Project Name -->
                                <div class="mb-6">
                                    <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Project Name <span class="text-red-500">*</span>
                                    </label>
                                    <input id="project_name" v-model="basicForm.project_name" type="text" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                        :class="basicForm.errors.project_name ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''" />
                                    <p v-if="basicForm.errors.project_name" class="mt-2 text-sm text-red-600">
                                        {{ basicForm.errors.project_name }}
                                    </p>
                                </div>

                                <!-- Short Description -->
                                <div class="mb-6">
                                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Short Description
                                    </label>
                                    <textarea id="short_description" v-model="basicForm.short_description" rows="3"
                                        maxlength="500"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors resize-none"
                                        :class="basicForm.errors.short_description ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''"></textarea>
                                    <div class="flex justify-between mt-1">
                                        <p v-if="basicForm.errors.short_description" class="text-sm text-red-600">
                                            {{ basicForm.errors.short_description }}
                                        </p>
                                        <p class="text-sm text-gray-500 text-right">
                                            {{ (basicForm.short_description || '').length }}/500
                                        </p>
                                    </div>
                                </div>

                                <!-- Parent Project -->
                                <div class="mb-6">
                                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Parent Project
                                    </label>
                                    <select id="parent_id" v-model="basicForm.parent_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                        :class="basicForm.errors.parent_id ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''">
                                        <option value="">-- No Parent (Top Level Project) --</option>
                                        <option v-for="parent in parentProjects" :key="parent.id" :value="parent.id">
                                            {{ parent.project_name }}
                                        </option>
                                    </select>
                                    <p v-if="basicForm.errors.parent_id" class="mt-2 text-sm text-red-600">
                                        {{ basicForm.errors.parent_id }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Changing this will affect the project hierarchy.
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="mb-6">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status
                                    </label>
                                    <select id="status" v-model="basicForm.status"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                        :class="basicForm.errors.status ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                    <p v-if="basicForm.errors.status" class="mt-2 text-sm text-red-600">
                                        {{ basicForm.errors.status }}
                                    </p>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <div class="space-y-1">
                                            <div><strong>Active:</strong> Visible to public</div>
                                            <div><strong>Inactive:</strong> Hidden from public but accessible to admin
                                            </div>
                                            <div><strong>Archived:</strong> Completely hidden</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Icon Upload -->
                                <div class="mb-6">
                                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                                        Project Icon
                                    </label>
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-20 w-20 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                                <img v-if="iconPreview || project.icon"
                                                    :src="iconPreview || `/storage/${project.icon}`" alt="Icon preview"
                                                    class="h-full w-full object-contain" />
                                                <ImageIcon v-else class="h-8 w-8 text-gray-400" />
                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <input id="icon" type="file" ref="iconInput" @change="handleIconChange"
                                                accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/webp"
                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors" />
                                            <p class="mt-1 text-sm text-gray-500">
                                                PNG, JPG, GIF, SVG, WebP up to 2MB. Recommended size: 64x64px or larger.
                                            </p>
                                            <button v-if="iconPreview || project.icon" type="button" @click="removeIcon"
                                                class="mt-2 text-sm text-red-600 hover:text-red-800">
                                                Remove icon
                                            </button>
                                        </div>
                                    </div>
                                    <p v-if="basicForm.errors.icon" class="mt-2 text-sm text-red-600">
                                        {{ basicForm.errors.icon }}
                                    </p>
                                </div>

                                <!-- Sort Order -->
                                <div class="mb-6">
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sort Order
                                    </label>
                                    <input id="sort_order" v-model.number="basicForm.sort_order" type="number" min="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                        :class="basicForm.errors.sort_order ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''" />
                                    <p v-if="basicForm.errors.sort_order" class="mt-2 text-sm text-red-600">
                                        {{ basicForm.errors.sort_order }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Lower numbers appear first in listings.
                                    </p>
                                </div>
                            </div>

                            <!-- Basic Form Actions -->
                            <div class="flex items-center justify-between">
                                <Link :href="route('admin.projects.index')"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to Projects
                                </Link>

                                <button type="submit" :disabled="basicForm.processing"
                                    class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <Loader2 v-if="basicForm.processing" class="w-4 h-4 mr-2 animate-spin" />
                                    <Save v-else class="w-4 h-4 mr-2" />
                                    {{ basicForm.processing ? 'Updating...' : 'Update Project' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Project Details Form -->
                    <div>
                        <div class="flex items-center mb-4">
                            <FileText class="w-5 h-5 text-gray-600 mr-2" />
                            <h2 class="text-xl font-semibold text-gray-900">Project Details</h2>
                        </div>

                        <form @submit.prevent="submitDetails" class="space-y-6">
                            <div class="bg-white rounded-lg shadow-sm border p-6">
                                <!-- Detail Title -->
                                <div class="mb-6">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Detail Title <span class="text-red-500">*</span>
                                    </label>
                                    <input id="title" v-model="detailsForm.title" type="text" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                        :class="detailsForm.errors.title ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''"
                                        placeholder="Main title for project details page" />
                                    <p v-if="detailsForm.errors.title" class="mt-2 text-sm text-red-600">
                                        {{ detailsForm.errors.title }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        This will be displayed as the main heading on the project details page.
                                    </p>
                                </div>

                                <!-- Content -->
                                <div class="mb-6">
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                        Content <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <textarea id="content" v-model="detailsForm.content" rows="15" required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none font-mono text-sm"
                                            :class="detailsForm.errors.content ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''"
                                            placeholder="Detailed project description (HTML supported)"></textarea>
                                        <div class="absolute top-2 right-2 text-xs text-gray-400">
                                            {{ (detailsForm.content || '').length }} characters
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500 space-y-1">
                                        <p><strong>HTML Support:</strong> You can use HTML tags for formatting.</p>
                                        <p><strong>Examples:</strong> &lt;p&gt;, &lt;br&gt;, &lt;strong&gt;, &lt;em&gt;,
                                            &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;a&gt;, &lt;img&gt;</p>
                                    </div>
                                    <p v-if="detailsForm.errors.content" class="mt-2 text-sm text-red-600">
                                        {{ detailsForm.errors.content }}
                                    </p>
                                </div>

                                <!-- Content Preview -->
                                <div v-if="detailsForm.content" class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Preview
                                    </label>
                                    <div class="p-4 border rounded-md bg-gray-50 max-h-40 overflow-y-auto">
                                        <div v-html="detailsForm.content" class="prose prose-sm max-w-none"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Details Form Actions -->
                            <div class="flex items-center justify-end">
                                <button type="submit" :disabled="detailsForm.processing"
                                    class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    <Loader2 v-if="detailsForm.processing" class="w-4 h-4 mr-2 animate-spin" />
                                    <Save v-else class="w-4 h-4 mr-2" />
                                    {{ detailsForm.processing ? 'Saving...' : 'Save Details' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <Link :href="route('projects.show', project.slug)" target="_blank"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <ExternalLink class="w-4 h-4 mr-2" />
                        View Project
                        </Link>

                        <button @click="duplicateProject" :disabled="duplicateProcessing"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition-colors">
                            <Copy class="w-4 h-4 mr-2" />
                            {{ duplicateProcessing ? 'Duplicating...' : 'Duplicate Project' }}
                        </button>

                        <button @click="deleteProject" :disabled="project.children && project.children.length > 0"
                            :class="project.children && project.children.length > 0
                                ? 'text-gray-400 bg-gray-50 border-gray-200 cursor-not-allowed'
                                : 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100 focus:ring-red-500'"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors">
                            <Trash2 class="w-4 h-4 mr-2" />
                            Delete Project
                        </button>
                    </div>

                    <div v-if="project.children && project.children.length > 0"
                        class="mt-2 text-sm text-amber-600 flex items-center">
                        <AlertTriangle class="w-4 h-4 mr-1" />
                        Cannot delete project with child projects
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeDeleteModal">
                    </div>

                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <AlertTriangle class="w-6 h-6 text-red-600" />
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Project</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Are you sure you want to delete "<span class="font-semibold">{{
                                                project.project_name }}</span>"?
                                            This action cannot be undone and will permanently remove the project and all
                                            its associated data.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="confirmDelete" type="button" :disabled="deleteProcessing"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 transition-colors">
                                <Loader2 v-if="deleteProcessing" class="w-4 h-4 mr-2 animate-spin" />
                                {{ deleteProcessing ? 'Deleting...' : 'Delete Project' }}
                            </button>
                            <button @click="closeDeleteModal" type="button" :disabled="deleteProcessing"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import {
    ImageIcon, ArrowLeft, Save, FileText, Settings, ExternalLink,
    Copy, Trash2, AlertTriangle, CheckCircle, Loader2
} from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    project: Object,
    parentProjects: Array
})

const iconInput = ref(null)
const iconPreview = ref(null)
const showDeleteModal = ref(false)
const deleteProcessing = ref(false)
const duplicateProcessing = ref(false)

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
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB')
            event.target.value = ''
            return
        }

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

function removeIcon() {
    basicForm.icon = null
    iconPreview.value = null
    if (iconInput.value) {
        iconInput.value.value = ''
    }
}

function submitBasicInfo() {
    basicForm.post(route('admin.projects.update', props.project.id), {
        _method: 'put',
        preserveScroll: true,
        onError: () => {
            // Scroll to first error field
            const firstErrorField = document.querySelector('.border-red-300')
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' })
                firstErrorField.focus()
            }
        }
    })
}

function submitDetails() {
    detailsForm.post(route('admin.projects.update-details', props.project.id), {
        preserveScroll: true,
        onError: () => {
            // Scroll to content field if there's an error
            const contentField = document.querySelector('#content')
            if (contentField) {
                contentField.scrollIntoView({ behavior: 'smooth', block: 'center' })
                contentField.focus()
            }
        }
    })
}

function duplicateProject() {
    duplicateProcessing.value = true

    // This would need a separate route in your controller
    router.post(route('admin.projects.duplicate', props.project.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            duplicateProcessing.value = false
        }
    })
}

function deleteProject() {
    if (props.project.children && props.project.children.length > 0) {
        return
    }
    showDeleteModal.value = true
}

function closeDeleteModal() {
    if (!deleteProcessing.value) {
        showDeleteModal.value = false
    }
}

function confirmDelete() {
    deleteProcessing.value = true

    router.delete(route('admin.projects.destroy', props.project.id), {
        onFinish: () => {
            deleteProcessing.value = false
            showDeleteModal.value = false
        }
    })
}

// Handle escape key to close modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && showDeleteModal.value) {
        closeDeleteModal()
    }
})
</script>

<style scoped>
/* Custom prose styles for preview */
.prose {
    color: #374151;
}

.prose p {
    margin-bottom: 1em;
}

.prose h1,
.prose h2,
.prose h3,
.prose h4,
.prose h5,
.prose h6 {
    font-weight: 600;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
}

.prose ul,
.prose ol {
    margin: 1em 0;
    padding-left: 1.5em;
}

.prose li {
    margin-bottom: 0.5em;
}

.prose a {
    color: #2563eb;
    text-decoration: underline;
}

.prose strong {
    font-weight: 600;
}

.prose em {
    font-style: italic;
}
</style>