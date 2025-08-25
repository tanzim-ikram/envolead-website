<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
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
              <Link v-else :href="route('projects.show', item.slug)" class="text-gray-500 hover:text-green-700">
              {{ item.project_name }}
              </Link>
            </li>
          </ol>
        </nav>
      </div>
    </div>

    <!-- Project Header -->
    <div class="bg-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-start">
          <img v-if="project.icon" :src="`/storage/${project.icon}`" :alt="project.project_name + ' icon'"
            class="h-20 w-20 object-contain mr-6 flex-shrink-0" />
          <div class="flex-grow">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ project.project_name }}</h1>
            <p v-if="project.short_description" class="text-xl text-gray-600">
              {{ project.short_description }}
            </p>

            <!-- Project Meta Info -->
            <div class="flex items-center mt-4 space-x-4">
              <span v-if="project.parent"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                Subproject of {{ project.parent.project_name }}
              </span>
              <span class="text-sm text-gray-500">
                Updated {{ formatDate(project.updated_at) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Project Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-white rounded-lg shadow-sm border">
        <div v-if="project.detail" class="p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ project.detail.title }}</h2>
          <div class="prose prose-lg max-w-none" v-html="project.detail.content"></div>
        </div>

        <!-- Empty State for No Details -->
        <div v-else class="p-8 text-center">
          <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m6 0h6m-6 6v6m0 0v6m0-6h6m-6 0H9" />
          </svg>
          <h3 class="mt-4 text-lg font-medium text-gray-900">No details available</h3>
          <p class="mt-2 text-gray-500">This project doesn't have detailed content yet.</p>
        </div>
      </div>

      <!-- Back to Projects -->
      <div class="mt-8 text-center">
        <Link :href="route('projects.index')"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 transition-colors">
        <ArrowLeft width="16" height="16" stroke-width="1.5" class="mr-2" />
        Back to Projects
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'

defineProps({
  project: Object,
  breadcrumb: Array
})

// Helper function to format date
function formatDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<style>
/* Custom prose styles for project content */
.prose {
  @apply text-gray-700;
}

.prose h1,
.prose h2,
.prose h3,
.prose h4 {
  @apply text-gray-900 font-bold;
}

.prose a {
  @apply text-green-700 hover:text-green-800;
}

.prose img {
  @apply rounded-lg shadow-sm;
}
</style>