<template>
  <AppLayout>
    <div class="p-6">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Create New Project</h1>
        <p class="text-gray-600">Add a new project to your portfolio</p>
      </div>

      <!-- Form -->
      <div class="max-w-2xl">
        <form @submit.prevent="submit" class="space-y-6">
          <div class="bg-white rounded-lg shadow p-6">
            <!-- Project Name -->
            <div class="mb-6">
              <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">
                Project Name *
              </label>
              <input
                id="project_name"
                v-model="form.project_name"
                type="text"
                required
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                placeholder="Enter project name"
              />
              <p v-if="form.errors.project_name" class="mt-1 text-sm text-red-600">
                {{ form.errors.project_name }}
              </p>
            </div>

            <!-- Short Description -->
            <div class="mb-6">
              <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                Short Description
              </label>
              <textarea
                id="short_description"
                v-model="form.short_description"
                rows="3"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                placeholder="Brief description of the project"
              ></textarea>
              <p v-if="form.errors.short_description" class="mt-1 text-sm text-red-600">
                {{ form.errors.short_description }}
              </p>
            </div>

            <!-- Parent Project -->
            <div class="mb-6">
              <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                Parent Project
              </label>
              <select
                id="parent_id"
                v-model="form.parent_id"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              >
                <option value="">-- No Parent (Top Level Project) --</option>
                <option v-for="parent in parentProjects" :key="parent.id" :value="parent.id">
                  {{ parent.project_name }}
                </option>
              </select>
              <p class="mt-1 text-sm text-gray-500">
                Select a parent project to make this a subproject
              </p>
              <p v-if="form.errors.parent_id" class="mt-1 text-sm text-red-600">
                {{ form.errors.parent_id }}
              </p>
            </div>

            <!-- Icon Upload -->
            <div class="mb-6">
              <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                Project Icon
              </label>
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <img
                    v-if="iconPreview"
                    :src="iconPreview"
                    alt="Icon preview"
                    class="h-16 w-16 object-contain rounded-lg border"
                  />
                  <div v-else class="h-16 w-16 bg-gray-100 rounded-lg border flex items-center justify-center">
                    <ImageIcon width="24" height="24" class="text-gray-400" />
                  </div>
                </div>
                <div class="flex-grow">
                  <input
                    id="icon"
                    type="file"
                    ref="iconInput"
                    @change="handleIconChange"
                    accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                  />
                  <p class="mt-1 text-sm text-gray-500">
                    PNG, JPG, GIF up to 2MB
                  </p>
                </div>
              </div>
              <p v-if="form.errors.icon" class="mt-1 text-sm text-red-600">
                {{ form.errors.icon }}
              </p>
            </div>

            <!-- Sort Order -->
            <div class="mb-6">
              <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                Sort Order
              </label>
              <input
                id="sort_order"
                v-model.number="form.sort_order"
                type="number"
                min="0"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                placeholder="Leave empty for auto-assignment"
              />
              <p class="mt-1 text-sm text-gray-500">
                Lower numbers appear first. Leave empty to auto-assign.
              </p>
              <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">
                {{ form.errors.sort_order }}
              </p>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex items-center justify-end space-x-3">
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50"
            >
              <span v-if="form.processing">Creating...</span>
              <span v-else>Create Project</span>
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
import { ImageIcon } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue';

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
        form.icon = file

        // Create preview
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

function submit() {
    form.post(route('admin.projects.store'))
}
</script><template>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Create New Project</h1>
            <p class="text-gray-600">Add a new project to your portfolio</p>
        </div>

        <!-- Form -->
        <div class="max-w-2xl">
            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Project Name -->
                    <div class="mb-6">
                        <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Name *
                        </label>
                        <input id="project_name" v-model="form.project_name" type="text" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="Enter project name" />
                        <p v-if="form.errors.project_name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.project_name }}
                        </p>
                    </div>

                    <!-- Short Description -->
                    <div class="mb-6">
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Short Description
                        </label>
                        <textarea id="short_description" v-model="form.short_description" rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="Brief description of the project"></textarea>
                        <p v-if="form.errors.short_description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.short_description }}
                        </p>
                    </div>

                    <!-- Parent Project -->
                    <div class="mb-6">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Project
                        </label>
                        <select id="parent_id" v-model="form.parent_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">-- No Parent (Top Level Project) --</option>
                            <option v-for="parent in parentProjects" :key="parent.id" :value="parent.id">
                                {{ parent.project_name }}
                            </option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">
                            Select a parent project to make this a subproject
                        </p>
                        <p v-if="form.errors.parent_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.parent_id }}
                        </p>
                    </div>

                    <!-- Icon Upload -->
                    <div class="mb-6">
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Icon
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img v-if="iconPreview" :src="iconPreview" alt="Icon preview"
                                    class="h-16 w-16 object-contain rounded-lg border" />
                                <div v-else
                                    class="h-16 w-16 bg-gray-100 rounded-lg border flex items-center justify-center">
                                    <ImageIcon width="24" height="24" class="text-gray-400" />
                                </div>
                            </div>
                            <div class="flex-grow">
                                <input id="icon" type="file" ref="iconInput" @change="handleIconChange" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100" />
                                <p class="mt-1 text-sm text-gray-500">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                            </div>
                        </div>
                        <p v-if="form.errors.icon" class="mt-1 text-sm text-red-600">
                            {{ form.errors.icon }}
                        </p>
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-6">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </label>
                        <input id="sort_order" v-model.number="form.sort_order" type="number" min="0"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="Leave empty for auto-assignment" />
                        <p class="mt-1 text-sm text-gray-500">
                            Lower numbers appear first. Leave empty to auto-assign.
                        </p>
                        <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">
                            {{ form.errors.sort_order }}
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-3">
                    <Link :href="route('dashboard.projects.manage')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Cancel
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50">
                        <span v-if="form.processing">Creating...</span>
                        <span v-else>Create Project</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ImageIcon } from 'lucide-vue-next'

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
    form.icon = file
    
    // Create preview
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

function submit() {
  form.post(route('dashboard.projects.store'))
}
</script>