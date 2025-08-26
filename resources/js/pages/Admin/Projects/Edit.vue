<template>

    <Head title="Edit Project" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Project</h1>
                    <p class="text-gray-600 mt-1">Update project information and details</p>
                </div>
                <Link :href="route('admin.projects.index')"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800 transition-colors duration-200">
                <ArrowLeft class="w-4 h-4 mr-2" />
                Back to Projects
                </Link>
            </div>

            <!-- Success Messages -->
            <div v-if="flashSuccess" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <CheckCircle class="w-5 h-5 text-green-600 mr-2" />
                    <p class="text-green-800">{{ flashSuccess }}</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 space-y-8">

                    <!-- Basic Project Info Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <FileText class="w-5 h-5 mr-2 text-gray-500" />
                                Basic Project Information
                            </h2>
                        </div>

                        <!-- Project Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                            <input v-model="form.project_name" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Enter project name" required />
                            <div v-if="form.errors.project_name" class="text-red-600 text-sm mt-1">{{
                                form.errors.project_name }}</div>
                        </div>

                        <!-- Parent Project & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Parent Project</label>
                                <select v-model="form.parent_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                                    <option value="">-- No Parent Project --</option>
                                    <option v-for="p in parentProjects" :key="p.id" :value="p.id">{{ p.project_name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.parent_id" class="text-red-600 text-sm mt-1">{{
                                    form.errors.parent_id }}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <Eye class="w-4 h-4 mr-1" />
                                    Status
                                </label>
                                <select v-model="form.status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="archived">Archived</option>
                                </select>
                                <div v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{ form.errors.status
                                    }}</div>
                                <div class="mt-2 text-sm text-gray-500">
                                    <div class="space-y-1">
                                        <div><strong>Active:</strong> Visible to public</div>
                                        <div><strong>Inactive:</strong> Hidden from public but accessible to admin</div>
                                        <div><strong>Archived:</strong> Completely hidden</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                            <textarea v-model="form.short_description" rows="3" maxlength="500"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Brief summary of the project"></textarea>
                            <div class="flex justify-between mt-1">
                                <div v-if="form.errors.short_description" class="text-red-600 text-sm">{{
                                    form.errors.short_description }}</div>
                                <p class="text-sm text-gray-500 text-right">
                                    {{ (form.short_description || '').length }}/500
                                </p>
                            </div>
                        </div>

                        <!-- Icon & Sort Order -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Project Icon</label>
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="h-16 w-16 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                                        <img v-if="iconPreview || project.icon"
                                            :src="iconPreview || `/storage/${project.icon}`"
                                            class="w-full h-full object-contain" alt="Icon Preview" />
                                        <ImageIcon v-else class="w-8 h-8 text-gray-400" />
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" accept="image/*" ref="iconInput" @change="handleIconChange"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors duration-200" />
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB. Recommended: 64x64px
                                        </p>
                                        <button v-if="iconPreview || project.icon" type="button" @click="removeIcon"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800">
                                            Remove icon
                                        </button>
                                    </div>
                                </div>
                                <div v-if="form.errors.icon" class="text-red-600 text-sm mt-1">{{ form.errors.icon }}
                                </div>
                            </div>
                            <!-- Sort Order Input - Fixed Version -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                <input v-model.number="form.sort_order" type="number" min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                    placeholder="Display order" />
                                <div v-if="form.errors.sort_order" class="text-red-600 text-sm mt-1">{{
                                    form.errors.sort_order }}</div>
                                <p class="text-xs text-gray-500 mt-1">Lower numbers appear first in listings</p>
                            </div>
                        </div>
                    </div>

                    <!-- Project Details Section (Optional) -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <FileText class="w-5 h-5 mr-2 text-gray-500" />
                                Project Details Page (Optional)
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Configure detailed content for the project's
                                individual page</p>
                        </div>

                        <!-- Detail Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detail Page Title</label>
                            <input v-model="form.detail_title" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Title for the detailed project page" />
                            <div v-if="form.errors.detail_title" class="text-red-600 text-sm mt-1">{{
                                form.errors.detail_title }}</div>
                        </div>

                        <!-- Detail Content -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detailed Content</label>
                            <div class="border border-gray-300 rounded-md overflow-hidden">
                                <QuillEditor v-model:content="form.detail_content" content-type="html" toolbar="full"
                                    theme="snow" style="min-height: 300px;" />
                            </div>
                            <div v-if="form.errors.detail_content" class="text-red-600 text-sm mt-1">{{
                                form.errors.detail_content }}</div>
                        </div>

                        <!-- Content Preview -->
                        <div v-if="form.detail_content" class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Preview</label>
                            <div class="p-4 border rounded-md bg-gray-50 max-h-40 overflow-y-auto">
                                <div v-html="form.detail_content" class="prose prose-sm max-w-none"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Objectives Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                    <Target class="w-5 h-5 mr-2 text-gray-500" />
                                    Project Objectives (Optional)
                                </h2>
                                <button type="button" @click="addObjective"
                                    class="inline-flex items-center px-3 py-1.5 text-sm text-green-700 hover:text-green-800 hover:bg-green-50 rounded-md transition-colors duration-200">
                                    <Plus class="w-4 h-4 mr-1" />
                                    Add Objective
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3" v-if="form.objectives.length > 0">
                            <div v-for="(objective, i) in form.objectives" :key="i"
                                class="flex items-start space-x-3 p-4 bg-gray-50 rounded-md border">
                                <div class="flex-1">
                                    <textarea v-model="form.objectives[i]" rows="2" :placeholder="`Objective ${i + 1}`"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"></textarea>
                                </div>
                                <button type="button" @click="removeObjective(i)"
                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                                    <Minus class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">Featured Quote (Optional)</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quote Text</label>
                                <textarea v-model="form.quote_text" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                    placeholder="Inspirational or relevant quote"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quote Author</label>
                                <input v-model="form.quote_author" type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                    placeholder="Author name" />
                            </div>
                        </div>
                    </div>

                    <!-- Key Highlights Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                    <Lightbulb class="w-5 h-5 mr-2 text-gray-500" />
                                    Key Highlights (Optional)
                                </h2>
                                <button type="button" @click="addHighlight"
                                    class="inline-flex items-center px-3 py-1.5 text-sm text-green-700 hover:text-green-800 hover:bg-green-50 rounded-md transition-colors duration-200">
                                    <Plus class="w-4 h-4 mr-1" />
                                    Add Highlight
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3" v-if="form.key_highlights.length > 0">
                            <div v-for="(highlight, i) in form.key_highlights" :key="i"
                                class="flex items-center space-x-3 p-4 bg-gray-50 rounded-md border">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <input v-model="highlight.label" placeholder="Label (e.g., Duration)"
                                        class="px-3 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200" />
                                    <input v-model="highlight.value" placeholder="Value (e.g., 3 Years)"
                                        class="px-3 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200" />
                                </div>
                                <button type="button" @click="removeHighlight(i)"
                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                                    <Minus class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">Featured Image</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-1">
                                <div
                                    class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                                    <img v-if="featuredImagePreview || project.detail?.featured_image"
                                        :src="featuredImagePreview || `/storage/${project.detail?.featured_image}`"
                                        class="w-full h-full object-cover" alt="Preview" />
                                    <div v-else class="text-center">
                                        <Upload class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                        <span class="text-gray-400 text-sm">Image Preview</span>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:col-span-2 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Featured
                                        Image</label>
                                    <input type="file" accept="image/*" @change="handleFeaturedImageChange"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors duration-200" />
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 5MB. Recommended: 1200x630px
                                    </p>
                                    <button v-if="featuredImagePreview || project.detail?.featured_image" type="button"
                                        @click="removeFeaturedImage"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Remove featured image
                                    </button>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Image Caption</label>
                                    <input v-model="form.image_caption" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                        placeholder="Optional image caption" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Images Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">Additional Images (Optional)</h2>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Additional Images</label>
                            <input type="file" accept="image/*" multiple @change="handleAdditionalImagesChange"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors duration-200" />
                            <p class="text-xs text-gray-500 mt-1">You can select multiple images at once</p>
                        </div>

                        <!-- Additional Images Preview -->
                        <div v-if="additionalImagePreviews.length > 0"
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="(preview, i) in additionalImagePreviews" :key="i" class="relative">
                                <img :src="preview" class="w-full h-32 object-cover rounded-md border" />
                                <button type="button" @click="removeAdditionalImage(i)"
                                    class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full hover:bg-red-700">
                                    <Minus class="w-3 h-3" />
                                </button>
                                <input v-model="form.image_captions[i]" type="text"
                                    class="w-full mt-2 px-2 py-1 text-sm border border-gray-300 rounded focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                    placeholder="Image caption" />
                            </div>
                        </div>
                    </div>

                    <!-- SEO Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">SEO Settings</h2>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea v-model="form.meta_description" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Brief description for search engines (optional)"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
                        </div>
                    </div>

                    <!-- Quick Actions Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">Quick Actions</h2>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <Link v-if="project.slug" :href="route('projects.show', project.slug)" target="_blank"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors duration-200">
                            <ExternalLink class="w-4 h-4 mr-2" />
                            View Project
                            </Link>

                            <button type="button" @click="duplicateProject" :disabled="duplicateProcessing"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 disabled:opacity-50 transition-colors duration-200">
                                <Copy class="w-4 h-4 mr-2" />
                                {{ duplicateProcessing ? 'Duplicating...' : 'Duplicate Project' }}
                            </button>

                            <button type="button" @click="deleteProject"
                                :disabled="project.children && project.children.length > 0" :class="project.children && project.children.length > 0
                                    ? 'text-gray-400 bg-gray-50 border-gray-200 cursor-not-allowed'
                                    : 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100'"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium border rounded-md transition-colors duration-200">
                                <Trash2 class="w-4 h-4 mr-2" />
                                Delete Project
                            </button>
                        </div>

                        <div v-if="project.children && project.children.length > 0"
                            class="text-sm text-amber-600 flex items-center">
                            <AlertTriangle class="w-4 h-4 mr-1" />
                            Cannot delete project with child projects
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <Link :href="route('admin.projects.index')"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center px-6 py-2 bg-green-700 hover:bg-green-800 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-md transition-colors duration-200">
                            <Save class="w-4 h-4 mr-2" />
                            {{ form.processing ? 'Updating...' : 'Update Project' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
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
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 disabled:opacity-50 transition-colors duration-200 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ deleteProcessing ? 'Deleting...' : 'Delete Project' }}
                            </button>
                            <button @click="closeDeleteModal" type="button" :disabled="deleteProcessing"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { Head, Link, useForm, router, usePage } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill'
import { ArrowLeft, Save, Plus, Minus, Upload, FileText, Eye, ImageIcon, Target, Lightbulb, ExternalLink, Copy, Trash2, AlertTriangle, CheckCircle } from 'lucide-vue-next'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import AppLayout from '@/layouts/AppLayout.vue'

interface ParentProject {
    id: number
    project_name: string
}

interface ProjectDetail {
    title?: string
    content?: string
    featured_image?: string
}

interface AdminProject {
    id: number
    project_name: string
    slug: string | null
    icon: string | null
    short_description: string | null
    parent_id: number | null
    sort_order: number | null
    status: string
    detail?: ProjectDetail
    children?: any[]
}

interface FlashMessages {
    success?: string
    error?: string
}

const props = defineProps<{
    project: AdminProject
    parentProjects: ParentProject[]
}>()

const page = usePage()
const iconInput = ref<HTMLInputElement>()
const iconPreview = ref<string | null>(null)
const featuredImagePreview = ref<string | null>(null)
const additionalImagePreviews = ref<string[]>([])
const showDeleteModal = ref(false)
const deleteProcessing = ref(false)
const duplicateProcessing = ref(false)

// Computed property to safely access flash messages
const flashSuccess = computed(() => {
    const flash = page.props.flash as FlashMessages | undefined
    return flash?.success
})

const form = useForm({
    project_name: props.project.project_name,
    short_description: props.project.short_description || '',
    parent_id: props.project.parent_id || '',
    icon: null as File | null,
    sort_order: props.project.sort_order || 0,
    status: props.project.status || 'active',

    // Detail page content (optional)
    detail_title: props.project.detail?.title || '',
    detail_content: props.project.detail?.content || '',
    featured_image: null as File | null,
    image_caption: '',
    quote_text: '',
    quote_author: '',
    key_highlights: [{ label: '', value: '' }] as { label: string; value: string }[],
    objectives: [''] as string[],
    additional_images: [] as File[],
    image_captions: [] as string[],
    meta_description: '',
})

const handleIconChange = (e: Event) => {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB')
            if (iconInput.value) iconInput.value.value = ''
            return
        }
        form.icon = file
        iconPreview.value = URL.createObjectURL(file)
    }
}

const removeIcon = () => {
    form.icon = null
    iconPreview.value = null
    if (iconInput.value) iconInput.value.value = ''
}

const handleFeaturedImageChange = (e: Event) => {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (file) {
        form.featured_image = file
        featuredImagePreview.value = URL.createObjectURL(file)
    }
}

const removeFeaturedImage = () => {
    form.featured_image = null
    featuredImagePreview.value = null
}

const handleAdditionalImagesChange = (e: Event) => {
    const files = Array.from((e.target as HTMLInputElement)?.files || [])
    files.forEach((file) => {
        form.additional_images.push(file)
        form.image_captions.push('')
        additionalImagePreviews.value.push(URL.createObjectURL(file))
    })
}

const removeAdditionalImage = (index: number) => {
    form.additional_images.splice(index, 1)
    form.image_captions.splice(index, 1)
    additionalImagePreviews.value.splice(index, 1)
}

const addHighlight = () => form.key_highlights.push({ label: '', value: '' })
const removeHighlight = (i: number) => form.key_highlights.splice(i, 1)

const addObjective = () => form.objectives.push('')
const removeObjective = (i: number) => form.objectives.splice(i, 1)

const submit = () => {
    // Create FormData manually to ensure proper file handling
    const formData = new FormData()

    // Add basic fields only if they have values or have changed
    if (form.project_name !== props.project.project_name) {
        formData.append('project_name', form.project_name)
    }

    if (form.short_description !== (props.project.short_description || '')) {
        formData.append('short_description', form.short_description)
    }

    if (form.parent_id !== (props.project.parent_id || '')) {
        formData.append('parent_id', form.parent_id.toString())
    }

    if (form.sort_order !== (props.project.sort_order || 0)) {
        formData.append('sort_order', form.sort_order.toString())
    }

    if (form.status !== (props.project.status || 'active')) {
        formData.append('status', form.status)
    }

    // Handle icon file
    if (form.icon) {
        formData.append('icon', form.icon)
    }

    // Add Laravel method spoofing for PUT request
    formData.append('_method', 'PUT')

    // Use POST with method spoofing instead of PUT for file uploads
    router.post(route('admin.projects.update', props.project.id), formData, {
        preserveScroll: true,
        onStart: () => {
            form.processing = true
        },
        onFinish: () => {
            form.processing = false
        },
        onError: (errors) => {
            // Set form errors
            form.errors = errors

            const firstErrorField = document.querySelector('.border-red-300')
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' })
                    ; (firstErrorField as HTMLElement).focus()
            }
        },
        onSuccess: () => {
            // Clear form errors on success
            form.errors = {}
        }
    })
}

const duplicateProject = () => {
    duplicateProcessing.value = true
    router.post(route('admin.projects.duplicate', props.project.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            duplicateProcessing.value = false
        }
    })
}

const deleteProject = () => {
    if (props.project.children && props.project.children.length > 0) return
    showDeleteModal.value = true
}

const closeDeleteModal = () => {
    if (!deleteProcessing.value) {
        showDeleteModal.value = false
    }
}

const confirmDelete = () => {
    deleteProcessing.value = true
    router.delete(route('admin.projects.destroy', props.project.id), {
        onFinish: () => {
            deleteProcessing.value = false
            showDeleteModal.value = false
        }
    })
}

const handleEsc = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && showDeleteModal.value) closeDeleteModal()
}

onMounted(() => document.addEventListener('keydown', handleEsc))
onBeforeUnmount(() => document.removeEventListener('keydown', handleEsc))

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/admin/projects' },
    { title: `Edit ${props.project.project_name}`, href: `/admin/projects/${props.project.id}/edit` },
]
</script>

<style>
/* Custom Quill editor styling */
.ql-toolbar {
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid #d1d5db !important;
}

.ql-container {
    border-left: none !important;
    border-right: none !important;
    border-bottom: none !important;
    font-size: 14px;
}

.ql-editor {
    min-height: 300px;
    padding: 16px;
}

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