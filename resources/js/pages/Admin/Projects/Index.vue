<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Projects</h1>
                    <p class="text-gray-600 mt-2">Create, edit, and organize your projects</p>
                </div>

                <Link :href="route('admin.projects.create')"
                    class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <Plus class="w-5 h-5 mr-2" />
                Add New Project
                </Link>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <CheckCircle class="w-5 h-5 text-green-600 mr-2" />
                    <p class="text-green-800">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div v-if="$page.props.flash.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <AlertCircle class="w-5 h-5 text-red-600 mr-2" />
                    <p class="text-red-800">{{ $page.props.flash.error }}</p>
                </div>
            </div>

            <!-- Projects Stats -->
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <Folder class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Projects</p>
                            <p class="text-2xl font-bold text-gray-900">{{ projects.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <FolderTree class="w-6 h-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Parent Projects</p>
                            <p class="text-2xl font-bold text-gray-900">{{ parentProjectsCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <GitBranch class="w-6 h-6 text-purple-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Subprojects</p>
                            <p class="text-2xl font-bold text-gray-900">{{ subProjectsCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="bg-white rounded-lg shadow-sm border">
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
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="project in organizedProjects" :key="project.id"
                                :class="project.parent_id ? 'bg-gray-25' : ''"
                                class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <!-- Indentation for subprojects -->
                                        <div v-if="project.parent_id" class="w-6 mr-2 flex justify-center">
                                            <ChevronRight class="w-4 h-4 text-gray-400" />
                                        </div>

                                        <!-- Project Icon -->
                                        <div class="flex-shrink-0 mr-3">
                                            <img v-if="project.icon" :src="`/storage/${project.icon}`"
                                                :alt="project.project_name"
                                                class="h-10 w-10 rounded-lg object-contain border" />
                                            <div v-else
                                                class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <Folder class="w-5 h-5 text-gray-400" />
                                            </div>
                                        </div>

                                        <!-- Project Info -->
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold text-gray-900 truncate">
                                                {{ project.project_name }}
                                            </div>
                                            <div class="text-sm text-gray-500 truncate" style="max-width: 200px;">
                                                {{ project.short_description || 'No description' }}
                                            </div>
                                            <div v-if="project.parent" class="text-xs text-gray-400 mt-1">
                                                Parent: {{ project.parent.project_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="project.parent_id"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <GitBranch class="w-3 h-3 mr-1" />
                                        Subproject
                                    </span>
                                    <span v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <FolderTree class="w-3 h-3 mr-1" />
                                        Parent
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
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

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <!-- View Button -->
                                        <Link :href="route('projects.show', project.slug)" target="_blank"
                                            class="inline-flex items-center p-1.5 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-md transition-colors"
                                            title="View Project">
                                        <Eye class="w-4 h-4" />
                                        </Link>

                                        <!-- Edit Button -->
                                        <Link :href="route('admin.projects.edit', project.id)"
                                            class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors"
                                            title="Edit Project">
                                        <Edit class="w-4 h-4" />
                                        </Link>

                                        <!-- Delete Button -->
                                        <button @click="deleteProject(project)"
                                            class="inline-flex items-center p-1.5 rounded-md transition-colors" :class="project.children && project.children.length > 0
                                                ? 'text-gray-400 cursor-not-allowed'
                                                : 'text-red-600 hover:text-red-900 hover:bg-red-50'" :title="project.children && project.children.length > 0
                                                    ? 'Cannot delete project with children'
                                                    : 'Delete Project'"
                                            :disabled="project.children && project.children.length > 0">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="projects.length === 0" class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <Folder class="w-12 h-12 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No projects yet</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                        Get started by creating your first project. You can organize them into parent and child
                        projects.
                    </p>
                    <Link :href="route('admin.projects.create')"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <Plus class="w-4 h-4 mr-2" />
                    Create Your First Project
                    </Link>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background Overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeDeleteModal">
                    </div>

                    <!-- Modal Content -->
                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <AlertTriangle class="w-6 h-6 text-red-600" />
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Delete Project
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Are you sure you want to delete
                                            <span class="font-semibold">"{{ projectToDelete?.project_name }}"</span>?
                                            This action cannot be undone and will permanently remove the project and all
                                            its associated data.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="confirmDelete" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                                :disabled="deleteProcessing">
                                <Loader2 v-if="deleteProcessing" class="w-4 h-4 mr-2 animate-spin" />
                                {{ deleteProcessing ? 'Deleting...' : 'Delete Project' }}
                            </button>
                            <button @click="closeDeleteModal" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                                :disabled="deleteProcessing">
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
import {
    Plus, Eye, Edit, Trash2, Folder, AlertTriangle, ChevronRight,
    CheckCircle, AlertCircle, FolderTree, GitBranch, Loader2
} from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    projects: Array
})

const showDeleteModal = ref(false)
const projectToDelete = ref(null)
const deleteProcessing = ref(false)

// Computed properties for stats
const parentProjectsCount = computed(() => {
    return props.projects.filter(p => !p.parent_id).length
})

const subProjectsCount = computed(() => {
    return props.projects.filter(p => p.parent_id).length
})

// Organize projects to show parent projects first, then their children
const organizedProjects = computed(() => {
    const parentProjects = props.projects.filter(p => !p.parent_id)
    const result = []

    parentProjects.forEach(parent => {
        result.push(parent)
        const children = props.projects
            .filter(p => p.parent_id === parent.id)
            .sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0))
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
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

function deleteProject(project) {
    // Check if project has children
    if (project.children && project.children.length > 0) {
        return // Button should already be disabled
    }

    projectToDelete.value = project
    showDeleteModal.value = true
}

function closeDeleteModal() {
    if (!deleteProcessing.value) {
        showDeleteModal.value = false
        projectToDelete.value = null
    }
}

function confirmDelete() {
    if (projectToDelete.value && !deleteProcessing.value) {
        deleteProcessing.value = true

        router.delete(route('admin.projects.destroy', projectToDelete.value.id), {
            preserveScroll: true,
            onFinish: () => {
                deleteProcessing.value = false
                showDeleteModal.value = false
                projectToDelete.value = null
            }
        })
    }
}

// Handle escape key to close modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && showDeleteModal.value) {
        closeDeleteModal()
    }
})
</script>

<style scoped>
.bg-gray-25 {
    background-color: #fafafa;
}

/* Custom scrollbar for the table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>