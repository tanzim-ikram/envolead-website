<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage Projects</h1>
                    <p class="text-gray-600">Create, edit, and organize your projects</p>
                </div>

                <Link :href="route('admin.projects.create')"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                <Plus width="20" height="20" class="mr-2" />
                Add New Project
                </Link>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ $page.props.flash.success }}</p>
            </div>

            <div v-if="$page.props.flash.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800">{{ $page.props.flash.error }}</p>
            </div>

            <!-- Projects Table -->
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Updated
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="project in organizedProjects" :key="project.id"
                                :class="project.parent_id ? 'bg-gray-25' : ''">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div v-if="project.parent_id" class="w-6 mr-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                        <img v-if="project.icon" :src="`/storage/${project.icon}`"
                                            :alt="project.project_name" class="h-8 w-8 rounded object-contain mr-3" />
                                        <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded mr-3 flex items-center justify-center"
                                            v-else>
                                            <Folder width="16" height="16" class="text-gray-400" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ project.project_name }}
                                            </div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{
                                                project.short_description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="project.parent_id"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Subproject
                                    </span>
                                    <span v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Parent Project
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getStatusClass(project.status)">
                                        {{ project.status || 'active' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ project.sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(project.updated_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <Link :href="route('projects.show', project.slug)" target="_blank"
                                            class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition-colors"
                                            title="View Project">
                                        <Eye width="16" height="16" />
                                        </Link>

                                        <Link :href="route('admin.projects.edit', project.id)"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors"
                                            title="Edit Project">
                                        <Edit width="16" height="16" />
                                        </Link>

                                        <button @click="deleteProject(project)"
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                            title="Delete Project"
                                            :disabled="project.children && project.children.length > 0"
                                            :class="project.children && project.children.length > 0 ? 'opacity-50 cursor-not-allowed' : ''">
                                            <Trash2 width="16" height="16" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="projects.length === 0" class="text-center py-12">
                    <Folder width="48" height="48" class="mx-auto text-gray-400 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No projects yet</h3>
                    <p class="text-gray-500 mb-4">Get started by creating your first project.</p>
                    <Link :href="route('admin.projects.create')"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <Plus width="20" height="20" class="mr-2" />
                    Create Project
                    </Link>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        @click="showDeleteModal = false"></div>

                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <AlertTriangle width="24" height="24" class="text-red-600" />
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Delete Project
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Are you sure you want to delete "{{ projectToDelete?.project_name }}"? This
                                            action cannot be undone.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="confirmDelete" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                            <button @click="showDeleteModal = false" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Plus, Eye, Edit, Trash2, Folder, AlertTriangle } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    projects: Array
})

const showDeleteModal = ref(false)
const projectToDelete = ref(null)

// Organize projects to show parent projects first, then their children
const organizedProjects = computed(() => {
    const parentProjects = props.projects.filter(p => !p.parent_id)
    const result = []

    parentProjects.forEach(parent => {
        result.push(parent)
        const children = props.projects.filter(p => p.parent_id === parent.id)
        result.push(...children)
    })

    return result
})

function getStatusClass(status) {
    const statusValue = status || 'active'
    switch (statusValue) {
        case 'active':
            return 'bg-green-100 text-green-800'
        case 'inactive':
            return 'bg-yellow-100 text-yellow-800'
        case 'archived':
            return 'bg-gray-100 text-gray-800'
        default:
            return 'bg-green-100 text-green-800'
    }
}

function formatDate(dateString) {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

function deleteProject(project) {
    projectToDelete.value = project
    showDeleteModal.value = true
}

function confirmDelete() {
    if (projectToDelete.value) {
        router.delete(route('admin.projects.destroy', projectToDelete.value.id), {
            preserveScroll: true,
            onFinish: () => {
                showDeleteModal.value = false
                projectToDelete.value = null
            }
        })
    }
}
</script>

<style scoped>
.bg-gray-25 {
    background-color: #fafafa;
}
</style>