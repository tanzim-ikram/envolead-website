<template>
  <Navbar />
  <div class="min-h-screen bg-white">
    <!-- Header with Back Button -->
    <div class="border-b border-gray-100">
      <div class="mx-auto px-4 sm:px-6 lg:px-[10%] py-6">
        <div class="flex items-center justify-between">
          <!-- Breadcrumb -->
          <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
              <li class="inline-flex items-center">
                <Link :href="route('projects.index')" class="text-gray-400 hover:text-gray-600 text-sm">
                Projects
                </Link>
              </li>
              <li v-for="(item, index) in breadcrumb" :key="item.id" class="inline-flex items-center">
                <svg class="w-4 h-4 text-gray-300 mx-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
                </svg>
                <span v-if="index === breadcrumb.length - 1" class="text-gray-600 text-sm font-medium">
                  {{ item.project_name }}
                </span>
                <Link v-else :href="route('projects.show', item.slug)"
                  class="text-gray-400 hover:text-gray-600 text-sm">
                {{ item.project_name }}
                </Link>
              </li>
            </ol>
          </nav>

          <!-- Back Button -->
          <Link :href="route('projects.index')"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 hover:text-green-900 transition-colors duration-200">
          <ArrowLeft width="16" height="16" class="mr-2" />
          Back to Projects
          </Link>
        </div>
      </div>
    </div>

    <!-- Project Header -->
    <div class="mx-auto px-4 sm:px-6 lg:px-[10%] py-12">
      <div class="max-w-4xl">
        <!-- Parent Project Name -->
        <p v-if="project.parent" class="text-sm text-green-700 mb-2">
          {{ project.parent.project_name }}
        </p>

        <!-- Main Project Title -->
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ project.project_name }}</h1>

        <p v-if="project.short_description" class="text-xl text-gray-600 mb-6 leading-relaxed">
          {{ project.short_description }}
        </p>

        <!-- Meta Info -->
        <div class="flex items-center space-x-4">
          <span v-if="project.status" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
            :class="{
              'bg-green-50 text-green-700 border border-green-200': project.status === 'active',
              'bg-blue-50 text-blue-700 border border-blue-200': project.status === 'completed',
              'bg-yellow-50 text-yellow-700 border border-yellow-200': project.status === 'on-hold',
              'bg-gray-50 text-gray-700 border border-gray-200': project.status === 'archived'
            }">
            {{ project.status.charAt(0).toUpperCase() + project.status.slice(1).replace('-', ' ') }}
          </span>
          <span class="text-sm text-gray-400">
            Updated {{ formatDate(project.updated_at) }}
          </span>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="mx-auto px-4 sm:px-6 lg:px-[10%] pb-20">
      <div>
        <div v-if="project.detail" class="space-y-16">

          <!-- Featured Image -->
          <div v-if="project.detail.featured_image" class="space-y-3">
            <img :src="`/storage/${project.detail.featured_image}`" :alt="project.project_name"
              class="w-full h-72 md:h-96 object-cover rounded-lg" />
            <p v-if="project.detail.image_caption" class="text-sm text-gray-500 italic">
              {{ project.detail.image_caption }}
            </p>
          </div>

          <!-- Project Description -->
          <div v-if="project.detail.excerpt" class="prose prose-xl max-w-none">
            <p class="text-gray-700 leading-relaxed">{{ project.detail.excerpt }}</p>
          </div>

          <!-- Project Objectives -->
          <div v-if="project.detail.objectives && project.detail.objectives.length" class="space-y-6">
            <h2 class="text-2xl font-bold text-gray-900">Project Objectives</h2>
            <div class="space-y-4">
              <div v-for="(objective, index) in project.detail.objectives" :key="index"
                class="flex items-start space-x-4">
                <span
                  class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-600 text-sm font-medium rounded-full flex-shrink-0 mt-1">
                  {{ String.fromCharCode(65 + index) }}
                </span>
                <p class="text-gray-700 leading-relaxed">{{ objective }}</p>
              </div>
            </div>
          </div>

          <!-- Rich Content -->
          <div v-if="project.detail.content" class="prose prose-lg max-w-none" v-html="project.detail.content"></div>

          <!-- Key Highlights -->
          <div v-if="project.detail.key_highlights && project.detail.key_highlights.length" class="space-y-6">
            <h2 class="text-2xl font-bold text-gray-900">Key Highlights</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div v-for="(highlight, index) in project.detail.key_highlights" :key="index"
                class="border-l-4 border-gray-200 pl-4 py-2">
                <div class="text-sm text-gray-500 font-medium mb-1">{{ highlight.label }}</div>
                <div class="text-2xl font-bold text-gray-900">{{ highlight.value }}</div>
              </div>
            </div>
          </div>

          <!-- Featured Quote -->
          <div v-if="project.detail.quote_text" class="border-l-4 border-gray-300 pl-8 py-6">
            <blockquote class="text-xl italic text-gray-800 mb-4">"{{ project.detail.quote_text }}"</blockquote>
            <footer v-if="project.detail.quote_author" class="text-gray-500 font-medium">
              — {{ project.detail.quote_author }}
            </footer>
          </div>

          <!-- Additional Images Gallery -->
          <div v-if="project.detail.additional_images && project.detail.additional_images.length" class="space-y-6">
            <h2 class="text-2xl font-bold text-gray-900">Project Gallery</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div v-for="(image, index) in project.detail.additional_images" :key="index" class="space-y-3">
                <img :src="`/storage/${image}`" :alt="`Project image ${index + 1}`"
                  class="w-full h-64 object-cover rounded-lg" />
                <p v-if="project.detail.image_captions && project.detail.image_captions[index]"
                  class="text-sm text-gray-500 italic">
                  {{ project.detail.image_captions[index] }}
                </p>
              </div>
            </div>
          </div>

        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-6">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-medium text-gray-900 mb-2">No details available</h3>
          <p class="text-gray-500 max-w-sm mx-auto">This project doesn't have detailed content yet. Contact us for more
            information about this project.</p>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script setup>
import Footer from '@/components/Footer.vue'
import Navbar from '@/components/Navbar.vue'
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
/* Minimal prose styling */
.prose h1,
.prose h2,
.prose h3 {
  color: #111827;
  font-weight: 700;
}

.prose h2 {
  margin-top: 2rem;
  margin-bottom: 1rem;
}

.prose p {
  color: #374151;
  line-height: 1.75;
}

.prose ul,
.prose ol {
  color: #374151;
}

.prose strong {
  color: #111827;
  font-weight: 600;
}

.prose a {
  color: #374151;
  text-decoration: underline;
  text-underline-offset: 4px;
}

.prose a:hover {
  color: #111827;
}

.prose blockquote {
  border-left: 4px solid #d1d5db;
  color: #374151;
  font-style: italic;
  padding-left: 1.5rem;
}
</style>