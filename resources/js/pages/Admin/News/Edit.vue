<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill'
import { ArrowLeft, Save, Plus, Minus, Upload, FileText, Calendar, Eye } from 'lucide-vue-next'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { ref } from 'vue'

const props = defineProps<{ news: any }>()

const form = useForm({
    title: props.news.title || '',
    excerpt: props.news.excerpt || '',
    content: props.news.content || '',
    featured_image: null as File | null,
    image_caption: props.news.image_caption || '',
    quote_text: props.news.quote_text || '',
    quote_author: props.news.quote_author || '',
    key_highlights: props.news.key_highlights && props.news.key_highlights.length > 0 ? props.news.key_highlights : [],
    published_at: props.news.published_at ? props.news.published_at.slice(0, 10) : '',
    status: props.news.status || 'draft',
    meta_description: props.news.meta_description || '',
})

const imagePreview = ref<string | null>(null)

const addHighlight = () => {
    if (!form.key_highlights) form.key_highlights = []
    form.key_highlights.push({ label: '', value: '' })
}
const removeHighlight = (i: number) => {
    if (form.key_highlights && form.key_highlights.length > i) {
        form.key_highlights.splice(i, 1)
    }
}

const handleImageChange = (e: Event) => {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (file) {
        form.featured_image = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

const submit = () => {
    // Simple validation
    if (!form.title || !form.title.trim()) {
        alert('Title is required')
        return
    }

    if (!form.content || !form.content.trim()) {
        alert('Content is required')
        return
    }

    if (!form.status) {
        alert('Status is required')
        return
    }

    console.log('Submitting form with data:', {
        title: form.title,
        content: form.content,
        status: form.status,
        key_highlights: form.key_highlights
    })

    // Use the correct method - either form.put() or form.post() with proper _method
    if (form.featured_image) {
        // If there's a file, use POST with _method override
        form.transform((data) => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.news.update', props.news.id), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                console.log('Update successful')
            },
            onError: (errors) => {
                console.log('Validation errors:', errors)
            },
            onFinish: () => {
                console.log('Request finished')
            }
        })
    } else {
        // If no file, use regular PUT
        form.put(route('admin.news.update', props.news.id), {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Update successful')
            },
            onError: (errors) => {
                console.log('Validation errors:', errors)
            },
            onFinish: () => {
                console.log('Request finished')
            }
        })
    }
}

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'News', href: '/admin/news' },
    { title: 'Edit News', href: '/admin/news' },
]
</script>

<template>

    <Head :title="`Edit: ${props.news.title}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit News Article</h1>
                    <p class="text-gray-600 mt-1">Update and manage your news article</p>
                </div>
                <Link :href="route('admin.news.index')"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800 transition-colors duration-200">
                <ArrowLeft class="w-4 h-4 mr-2" />
                Back to News
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 space-y-8">

                    <!-- Article Details Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <FileText class="w-5 h-5 mr-2 text-gray-500" />
                                Article Details
                            </h2>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Article Title *</label>
                            <input v-model="form.title" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Enter article title" required />
                            <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Published date & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <Calendar class="w-4 h-4 mr-1" />
                                    Published Date
                                </label>
                                <input v-model="form.published_at" type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200" />
                                <p class="text-xs text-gray-500 mt-1">Article publication date</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <Eye class="w-4 h-4 mr-1" />
                                    Status *
                                </label>
                                <select v-model="form.status" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                                <div v-if="form.errors.status" class="text-red-600 text-sm mt-1">{{ form.errors.status
                                }}
                                </div>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Short Excerpt</label>
                            <textarea v-model="form.excerpt" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200"
                                placeholder="Brief summary of the article (optional)"></textarea>
                            <p class="text-xs text-gray-500 mt-1">This will appear in article previews</p>
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
                                    <!-- Show new preview if file selected, otherwise show existing image -->
                                    <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover"
                                        alt="New Preview" />
                                    <img v-else-if="props.news.featured_image_url" :src="props.news.featured_image_url"
                                        class="w-full h-full object-cover" alt="Current Image" />
                                    <div v-else class="text-center">
                                        <Upload class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                        <span class="text-gray-400 text-sm">No Image</span>
                                    </div>
                                </div>
                                <p v-if="props.news.featured_image_url && !imagePreview"
                                    class="text-xs text-gray-500 mt-2 text-center">
                                    Current Image
                                </p>
                                <p v-else-if="imagePreview" class="text-xs text-green-600 mt-2 text-center font-medium">
                                    New Image Selected
                                </p>
                            </div>
                            <div class="lg:col-span-2 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ props.news.featured_image ? 'Replace Image' : 'Upload Image' }}
                                    </label>
                                    <input type="file" accept="image/*" @change="handleImageChange"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors duration-200" />
                                    <div v-if="form.errors.featured_image" class="text-red-600 text-sm mt-1">{{
                                        form.errors.featured_image }}</div>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 5MB. Recommended: 1200x630px
                                    </p>
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

                    <!-- Content Section -->
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900">Article Content</h2>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Article Body *</label>
                            <div class="border border-gray-300 rounded-md overflow-hidden">
                                <QuillEditor v-model:content="form.content" content-type="html" toolbar="full"
                                    theme="snow" style="min-height: 300px;" />
                            </div>
                            <div v-if="form.errors.content" class="text-red-600 text-sm mt-1">{{ form.errors.content }}
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
                                <h2 class="text-lg font-medium text-gray-900">Key Highlights (Optional)</h2>
                                <button type="button" @click="addHighlight"
                                    class="inline-flex items-center px-3 py-1.5 text-sm text-green-700 hover:text-green-800 hover:bg-green-50 rounded-md transition-colors duration-200">
                                    <Plus class="w-4 h-4 mr-1" />
                                    Add Highlight
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3" v-if="form.key_highlights && form.key_highlights.length > 0">
                            <div v-for="(highlight, i) in form.key_highlights" :key="i"
                                class="flex items-center space-x-3 p-4 bg-gray-50 rounded-md border">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <input v-model="highlight.label" placeholder="Label (e.g., Location)"
                                        class="px-3 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200" />
                                    <input v-model="highlight.value" placeholder="Value (e.g., 8 Divisions)"
                                        class="px-3 py-2 border border-gray-300 rounded-md focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 transition-colors duration-200" />
                                </div>
                                <button type="button" @click="removeHighlight(i)"
                                    class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                                    <Minus class="w-4 h-4" />
                                </button>
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
                        <Link :href="route('admin.news.index')"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center px-6 py-2 bg-green-700 hover:bg-green-800 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-md transition-colors duration-200">
                            <Save class="w-4 h-4 mr-2" />
                            {{ form.processing ? 'Saving...' : 'Update Article' }}
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