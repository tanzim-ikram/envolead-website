<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill'
import { ArrowLeft, Save, ImageIcon } from 'lucide-vue-next'
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
    detail_title: '',
    detail_content: ''
})

const iconPreview = ref<string | null>(null)

const handleIconChange = (e: Event) => {
    const file = (e.target as HTMLInputElement)?.files?.[0]
    if (file) {
        form.icon = file
        iconPreview.value = URL.createObjectURL(file)
    }
}

const submit = () => {
    form.post(route('admin.projects.store'), { forceFormData: true })
}

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/admin/projects' },
    { title: 'Add Project', href: '/admin/projects/create' },
];
</script>

<template>

    <Head title="Create Project" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Create Project</h1>
                    <p class="text-gray-600 mt-1">Add a new project and its details</p>
                </div>
                <Link :href="route('admin.projects.index')"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">
                <ArrowLeft class="w-4 h-4 mr-2" /> Back
                </Link>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <form @submit.prevent="submit" class="space-y-8">

                    <!-- Basic Info -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project Name *</label>
                        <input v-model="form.project_name" type="text"
                            class="w-full border rounded px-3 py-2 mt-1 focus:ring-green-500 focus:border-green-500"
                            required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Short Description</label>
                        <textarea v-model="form.short_description" rows="3"
                            class="w-full border rounded px-3 py-2 mt-1 focus:ring-green-500 focus:border-green-500" />
                    </div>

                    <!-- Parent -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Parent Project</label>
                        <select v-model="form.parent_id"
                            class="w-full border rounded px-3 py-2 mt-1 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- No Parent --</option>
                            <option v-for="p in parentProjects" :key="p.id" :value="p.id">{{ p.project_name }}</option>
                        </select>
                    </div>

                    <!-- Icon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project Icon</label>
                        <div class="flex items-center space-x-4 mt-1">
                            <img v-if="iconPreview" :src="iconPreview"
                                class="h-16 w-16 object-contain border rounded" />
                            <div v-else class="h-16 w-16 flex items-center justify-center border rounded bg-gray-100">
                                <ImageIcon class="text-gray-400" />
                            </div>
                            <input type="file" accept="image/*" @change="handleIconChange" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                       file:bg-green-50 file:text-green-700 hover:file:bg-green-100" />
                        </div>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input v-model="form.sort_order" type="number" min="0"
                            class="w-full border rounded px-3 py-2 mt-1 focus:ring-green-500 focus:border-green-500" />
                    </div>

                    <!-- Project Details -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Project Details</h2>
                        <label class="block text-sm font-medium text-gray-700">Detail Title</label>
                        <input v-model="form.detail_title" type="text"
                            class="w-full border rounded px-3 py-2 mt-1 focus:ring-green-500 focus:border-green-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Content</label>
                        <QuillEditor v-model:content="form.detail_content" content-type="html" theme="snow"
                            style="min-height: 300px;" />
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 border-t pt-4">
                        <Link :href="route('admin.projects.index')"
                            class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</Link>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">
                            <Save class="w-4 h-4 mr-2" />
                            {{ form.processing ? 'Saving...' : 'Save Project' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
