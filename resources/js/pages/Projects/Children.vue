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

        <!-- Children Projects Grid - Matching EnvoComms Design -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div v-if="children.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="child in children" :key="child.id"
                    class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden flex flex-col">
                    <div class="p-6 flex-grow">
                        <img :src="child.icon ? `/storage/${child.icon}` : '/images/default-project-icon.png'"
                            :alt="child.project_name + ' icon'" class="h-16 w-16 object-contain mx-auto mb-4" />

                        <h3 class="text-xl font-bold text-green-800 text-center">{{ child.project_name }}</h3>
                        <p v-if="child.short_description" class="text-gray-600 text-sm mt-2 text-center">
                            {{ child.short_description }}
                        </p>
                    </div>

                    <div class="px-6 pb-6">
                        <Link :href="route('projects.show', child.slug)"
                            class="text-green-700 font-semibold text-sm flex justify-center items-center hover:underline">
                        View project details
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