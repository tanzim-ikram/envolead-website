<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Breadcrumb -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('projects.index')" class="text-gray-500 hover:text-green-700">
                            Projects
                            </Link>
                        </li>
                        <li v-for="(item, index) in breadcrumb" :key="item.id" class="inline-flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span v-if="index === breadcrumb.length - 1" class="text-gray-900 font-medium">
                                {{ item.project_name }}
                            </span>
                            <Link v-else :href="route('projects.show', item.slug)"
                                class="text-gray-500 hover:text-green-700">
                            {{ item.project_name }}
                            </Link>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header -->
        <div class="bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center">
                    <img v-if="project.icon" :src="`/storage/${project.icon}`" :alt="project.project_name + ' icon'"
                        class="h-16 w-16 object-contain mr-4" />
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ project.project_name }}</h1>
                        <p v-if="project.short_description" class="text-lg text-gray-600 mt-1">
                            {{ project.short_description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Children Projects Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div v-if="children.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div v-for="child in children" :key="child.id"
                    class="bg-white rounded-lg shadow hover:shadow-md transition-all duration-200 hover:-translate-y-1 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <img :src="child.icon ? `/storage/${child.icon}` : '/images/default-project-icon.png'"
                                :alt="child.project_name + ' icon'" class="h-12 w-12 object-contain mr-3" />
                            <h3 class="text-lg font-semibold text-gray-900">{{ child.project_name }}</h3>
                        </div>

                        <p v-if="child.short_description" class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ child.short_description }}
                        </p>

                        <Link :href="route('projects.show', child.slug)"
                            class="inline-flex items-center text-green-700 hover:text-green-800 font-medium text-sm transition-colors">
                        View Details
                        <MoveRight width="16" height="16" stroke-width="1.5" class="ml-1" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4v44l8-4m0-36l8-4v44l-8 4m0-36v36" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No subprojects yet</h3>
                    <p class="mt-2 text-gray-500">This project doesn't have any subprojects at the moment.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { MoveRight } from 'lucide-vue-next'

defineProps({
    project: Object,
    children: Array,
    breadcrumb: Array
})
</script>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>