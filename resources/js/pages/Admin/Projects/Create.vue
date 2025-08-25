<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <Link :href="route('admin.projects.index')" class="hover:text-gray-700">Projects</Link>
                    <span>/</span>
                    <span class="text-gray-900">Create New Project</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Project</h1>
                <p class="text-gray-600 mt-2">Add a new project to your portfolio</p>
            </div>

            <!-- Form -->
            <div class="max-w-2xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <!-- Project Name -->
                        <div class="mb-6">
                            <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Project Name <span class="text-red-500">*</span>
                            </label>
                            <input id="project_name" v-model="form.project_name" type="text" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                :class="form.errors.project_name ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''"
                                placeholder="Enter project name" />
                            <p v-if="form.errors.project_name" class="mt-2 text-sm text-red-600">
                                {{ form.errors.project_name }}
                            </p>
                        </div>

                        <!-- Short Description -->
                        <div class="mb-6">
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Short Description
                            </label>
                            <textarea id="short_description" v-model="form.short_description" rows="3" maxlength="500"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors resize-none"
                                :class="form.errors.short_description ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''"
                                placeholder="Brief description of the project"></textarea>
                            <div class="flex justify-between mt-1">
                                <p v-if="form.errors.short_description" class="text-sm text-red-600">
                                    {{ form.errors.short_description }}
                                </p>
                                <p class="text-sm text-gray-500 text-right">
                                    {{ (form.short_description || '').length }}/500
                                </p>
                            </div>
                        </div>

                        <!-- Parent Project -->
                        <div class="mb-6">
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Parent Project
                            </label>
                            <select id="parent_id" v-model="form.parent_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                :class="form.errors.parent_id ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''">
                                <option value="">-- No Parent (Top Level Project) --</option>
                                <option v-for="parent in parentProjects" :key="parent.id" :value="parent.id">
                                    {{ parent.project_name }}
                                </option>
                            </select>
                            <p v-if="form.errors.parent_id" class="mt-2 text-sm text-red-600">
                                {{ form.errors.parent_id }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Leave empty to create a top-level project, or select a parent to create a subproject.
                            </p>
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
                                        <img v-if="iconPreview" :src="iconPreview" alt="Icon preview"
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
                                    <button v-if="iconPreview" type="button" @click="removeIcon"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Remove icon
                                    </button>
                                </div>
                            </div>
                            <p v-if="form.errors.icon" class="mt-2 text-sm text-red-600">
                                {{ form.errors.icon }}
                            </p>
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-6">
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>
                            <input id="sort_order" v-model.number="form.sort_order" type="number" min="0"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors"
                                :class="form.errors.sort_order ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : ''"
                                placeholder="Leave empty for auto-assignment" />
                            <p v-if="form.errors.sort_order" class="mt-2 text-sm text-red-600">
                                {{ form.errors.sort_order }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                Lower numbers appear first. Leave empty to automatically assign the next available
                                order.
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between">
                        <Link :href="route('admin.projects.index')"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Cancel
                        </Link>

                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                            <Plus v-else class="w-4 h-4 mr-2" />
                            {{ form.processing ? 'Creating...' : 'Create Project' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ImageIcon, ArrowLeft, Plus, Loader2 } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    parentProjects: Array
})

const iconInput = ref(null)
const iconPreview = ref(null)

const form = useForm({
    project_name: '',
    short_description: '',
    parent_id: '',
    icon: null,
    sort_order: null
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

        form.icon = file
        const reader = new FileReader()
        reader.onload = (e) => {
            iconPreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    } else {
        form.icon = null
        iconPreview.value = null
    }
}

function removeIcon() {
    form.icon = null
    iconPreview.value = null
    if (iconInput.value) {
        iconInput.value.value = ''
    }
}

function submit() {
    form.post(route('admin.projects.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Form will redirect to admin.projects.index on success
        },
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
</script>