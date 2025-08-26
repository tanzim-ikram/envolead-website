<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill'
import { ArrowLeft, Save, Plus, Minus, Upload, FileText, Calendar, Eye, ImageIcon, Target, Lightbulb } from 'lucide-vue-next'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { ref } from 'vue'

interface ParentProject {
    id: number
    project_name: string
}

const props = defineProps<{
    parentProjects: ParentProject[]
}>()

const form = useForm({
    project_name: '',
    short_description: '',
    parent_id: '',
    icon: null as File | null,
    sort_order: null as number | null,
    
    // Detail page content (optional)
    detail_title: '',
    detail_excerpt: '',
    detail_content: '',
    featured_image: null as File | null,
    image_caption: '',
    quote_text: '',
    quote_author: '',
    key_highlights: [{ label: '', value: '' }] as { label: string; value: string }[],
    objectives: [''] as string[],
    additional_images: [] as File[],
    image_captions: [] as string[],
    meta_description: '',
    status: 'active'
})

const iconPreview = ref<string | null>(null)
const featuredImagePreview = ref<string | null>(null)
const additionalImagePreviews = ref<string[]>([])

const handleIconChange = (e: Event) => {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (file) {
        form.icon = file
        iconPreview.value = URL.createObjectURL(file)
    }
}

const handleFeaturedImageChange = (e: Event) => {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (file) {
        form.featured_image = file
        featuredImagePreview.value = URL.createObjectURL(file)
    }
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
    form.post(route('admin.projects.store'), { forceFormData: true })
}

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/admin/projects' },
    { title: 'Create Project', href: '/admin/projects/create' },
]
</script>

<template>
    <Head title="Create Project" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Create Project</h1>
                    <p class="text-gray-600 mt-1">Add a new project with comprehensive details</p>
                </div>
                <Link :href="route('admin.projects.index')"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800 transition-colors duration-200">
                <ArrowLeft class="w-4 h-4 mr-2" />
                Back to Projects
                </Link>
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
                            <div v-if="form.errors.project_name" class="text-red-600 text-sm mt-1">{{ form.errors.project_name }}</div>
                        </div>

                        <!-- Parent Project & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Parent Project</label>
                                <select v-model="form.parent_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                                    <option value="">-- No Parent Project --</option>
                                    <option v-for="p in parentProjects" :key="p.id" :value="p.id">{{ p.project_name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <Eye class="w-4 h-4 mr-1" />
                                    Status
                                </label>
                                <select v-model="form.status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="on-hold">On Hold</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                            <textarea v-model="form.short_description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Brief summary of the project"></textarea>
                            <p class="text-xs text-gray-500 mt-1">This will appear in project previews</p>
                        </div>

                        <!-- Icon & Sort Order -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Project Icon</label>
                                <div class="flex items-center space-x-4">
                                    <div class="h-16 w-16 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                                        <img v-if="iconPreview" :src="iconPreview" class="w-full h-full object-contain" alt="Icon Preview" />
                                        <ImageIcon v-else class="w-8 h-8 text-gray-400" />
                                    </div>
                                    <input type="file" accept="image/*" @change="handleIconChange"
                                        class="block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors duration-200" />
                                </div>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB. Recommended: 64x64px</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                <input v-model="form.sort_order" type="number" min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                    placeholder="Display order" />
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
                            <p class="text-sm text-gray-600 mt-1">Configure detailed content for the project's individual page</p>
                        </div>

                        <!-- Detail Title & Excerpt -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detail Page Title</label>
                            <input v-model="form.detail_title" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Title for the detailed project page" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detail Excerpt</label>
                            <textarea v-model="form.detail_excerpt" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Brief overview for the detail page"></textarea>
                        </div>
                    </div>

                    <!-- Featured Image Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">Featured Image</h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-1">
                                <div class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                                    <img v-if="featuredImagePreview" :src="featuredImagePreview" class="w-full h-full object-cover" alt="Preview" />
                                    <div v-else class="text-center">
                                        <Upload class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                        <span class="text-gray-400 text-sm">Image Preview</span>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:col-span-2 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Featured Image</label>
                                    <input type="file" accept="image/*" @change="handleFeaturedImageChange"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors duration-200" />
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 5MB. Recommended: 1200x630px</p>
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

                    <!-- Project Content Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">Project Content</h2>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Detailed Content</label>
                            <div class="border border-gray-300 rounded-md overflow-hidden">
                                <QuillEditor v-model:content="form.detail_content" content-type="html" toolbar="full" theme="snow" style="min-height: 300px;" />
                            </div>
                            <div v-if="form.errors.detail_content" class="text-red-600 text-sm mt-1">{{ form.errors.detail_content }}</div>
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
                                    <textarea v-model="form.objectives[i]" rows="2"
                                        :placeholder="`Objective ${i + 1}`"
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
                        <div v-if="additionalImagePreviews.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <Link :href="route('admin.projects.index')"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center px-6 py-2 bg-green-700 hover:bg-green-800 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-md transition-colors duration-200">
                            <Save class="w-4 h-4 mr-2" />
                            {{ form.processing ? 'Saving...' : 'Save Project' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

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
</style>