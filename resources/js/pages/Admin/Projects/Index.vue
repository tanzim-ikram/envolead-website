<template>
    <AppLayout :breadcrumbs="breadcrumbs">
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

            <!-- Flash Messages -->
            <!-- <div v-if="flashSuccess"
                class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                <CheckCircle class="w-5 h-5 text-green-600 mr-2" />
                <p class="text-green-800">{{ flashSuccess }}</p>
            </div>
            <div v-if="flashError"
                class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                <AlertCircle class="w-5 h-5 text-red-600 mr-2" />
                <p class="text-red-800">{{ flashError }}</p>
            </div> -->

            <!-- Projects Table -->
            <div class="bg-white rounded-lg shadow-sm border overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Project
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                            :class="project.parent_id ? 'bg-gray-25' : ''" class="hover:bg-gray-50 transition-colors">
                            <!-- Project Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <!-- Indent for subprojects -->
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

                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 truncate">
                                            {{ project.project_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">
                                            {{ project.short_description || 'No description' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Type -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="project.parent_id"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <GitBranch class="w-3 h-3 mr-1" /> Subproject
                                </span>
                                <span v-else
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <FolderTree class="w-3 h-3 mr-1" /> Parent
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                    :class="getStatusClass(project.status)">
                                    {{ project.status || 'active' }}
                                </span>
                            </td>

                            <!-- Order -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ project.sort_order }}
                            </td>

                            <!-- Updated -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatDate(project.updated_at) }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    <Link v-if="project.slug" :href="route('projects.show', project.slug)"
                                        target="_blank"
                                        class="inline-flex items-center p-1.5 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-md transition-colors"
                                        title="View Project">
                                    <Eye class="w-4 h-4" />
                                    </Link>

                                    <!-- Edit -->
                                    <Link :href="route('admin.projects.edit', project.id)"
                                        class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors"
                                        title="Edit Project">
                                    <Edit class="w-4 h-4" />
                                    </Link>

                                    <!-- Delete -->
                                    <button @click="deleteProject(project)"
                                        class="inline-flex items-center p-1.5 rounded-md transition-colors" :class="project.children && project.children.length > 0
                                            ? 'text-gray-400 cursor-not-allowed'
                                            : 'text-red-600 hover:text-red-900 hover:bg-red-50'"
                                        :disabled="project.children && project.children.length > 0" :title="project.children && project.children.length > 0
                                            ? 'Cannot delete project with children'
                                            : 'Delete Project'">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div v-if="projects.length === 0" class="text-center py-12">
                    <Folder class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No projects yet</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first project.</p>
                    <Link :href="route('admin.projects.create')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <Plus class="w-4 h-4 mr-2" /> Create Project
                    </Link>
                </div>
            </div>

            <!-- Delete Modal -->
            <div v-if="showDeleteModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Delete Project</h2>
                    <p class="text-sm text-gray-600 mb-6">
                        Are you sure you want to delete
                        <span class="font-semibold">"{{ projectToDelete?.project_name }}"</span>?
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button @click="confirmDelete" :disabled="deleteProcessing"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50">
                            {{ deleteProcessing ? 'Deleting...' : 'Delete' }}
                        </button>
                        <button @click="closeDeleteModal" :disabled="deleteProcessing"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Plus, Eye, Edit, Trash2, Folder, ChevronRight, CheckCircle, AlertCircle, FolderTree, GitBranch } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/admin/projects' },
    { title: 'Manage Project', href: '/admin/projects' },
];

type ProjectStatus = 'active' | 'inactive' | 'archived'

interface ChildLite {
    id: number
    parent_id: number | null
    sort_order: number | null
}

interface AdminProject {
    id: number
    project_name: string
    slug: string | null
    icon: string | null
    short_description: string | null
    parent_id: number | null
    sort_order: number | null
    status: ProjectStatus | undefined
    updated_at: string | null
    children: ChildLite[]
}

const props = defineProps<{ projects: AdminProject[] }>()

// Flash helpers for typed access
const flashSuccess = computed(() => (typeof window !== 'undefined' ? (window as any)?.Inertia?.page?.props?.flash?.success : undefined) || ({} as any))
const flashError = computed(() => (typeof window !== 'undefined' ? (window as any)?.Inertia?.page?.props?.flash?.error : undefined) || ({} as any))


const showDeleteModal = ref(false)
const projectToDelete = ref<AdminProject | null>(null)
const deleteProcessing = ref(false)

// Computed: organize projects
const organizedProjects = computed<AdminProject[]>(() => {
    const parents: AdminProject[] = props.projects.filter((p: AdminProject) => !p.parent_id)
    const result: AdminProject[] = []
    parents.forEach((parent: AdminProject) => {
        result.push(parent)
        const children: AdminProject[] = props.projects
            .filter((p: AdminProject) => p.parent_id === parent.id)
            .sort((a: AdminProject, b: AdminProject) => (a.sort_order || 0) - (b.sort_order || 0))
        result.push(...children)
    })
    return result
})

// Helpers
function getStatusClass(status: ProjectStatus | undefined) {
    switch (status || 'active') {
        case 'active': return 'bg-green-100 text-green-800'
        case 'inactive': return 'bg-yellow-100 text-yellow-800'
        case 'archived': return 'bg-gray-100 text-gray-800'
        default: return 'bg-green-100 text-green-800'
    }
}

function formatDate(dateString: string | null) {
    if (!dateString) return ''
    const d = new Date(dateString)
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

// Delete functions
function deleteProject(project: AdminProject) {
    if (project.children && project.children.length > 0) return
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
    if (!projectToDelete.value) return
    deleteProcessing.value = true
    router.delete(route('admin.projects.destroy', projectToDelete.value.id), {
        onFinish: () => {
            deleteProcessing.value = false
            closeDeleteModal()
        }
    })
}

// Escape key close
const handleEsc = (e: KeyboardEvent) => { if (e.key === 'Escape' && showDeleteModal.value) closeDeleteModal() }
onMounted(() => document.addEventListener('keydown', handleEsc))
onBeforeUnmount(() => document.removeEventListener('keydown', handleEsc))
</script>
