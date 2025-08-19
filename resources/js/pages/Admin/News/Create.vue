<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

const form = useForm({
  title: '',
  excerpt: '',
  content: '',                 // HTML from editor
  featured_image: null as File | null,
  image_caption: '',
  quote_text: '',
  quote_author: '',
  key_highlights: [{ label: '', value: '' }] as { label: string; value: string }[],
  published_at: new Date().toISOString().slice(0, 10), // yyyy-mm-dd (editable)
  status: 'draft',
  meta_description: '',
})

const addHighlight = () => form.key_highlights.push({ label: '', value: '' })
const removeHighlight = (i: number) => form.key_highlights.splice(i, 1)

const submit = () => {
  form.post(route('admin.news.store'), { forceFormData: true })
}
</script>

<template>

  <Head title="Post News" />
  <AppLayout>
    <div class="p-6 space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Post News</h1>
        <Link :href="route('admin.news.index')" class="text-green-700 hover:underline">Back to Manage</Link>
      </div>

      <form @submit.prevent="submit" class="bg-white border rounded-lg p-6 space-y-6">
        <!-- Title -->
        <div>
          <label class="block text-sm font-medium mb-1">Title</label>
          <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2" required />
          <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</div>
        </div>

        <!-- Published date & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Published Date</label>
            <input v-model="form.published_at" type="date" class="w-full border rounded px-3 py-2" />
            <p class="text-xs text-gray-500 mt-1">Defaults to today, but you can change it.</p>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select v-model="form.status" class="w-full border rounded px-3 py-2">
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="archived">Archived</option>
            </select>
          </div>
        </div>

        <!-- Featured image + caption -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Featured Image</label>
            <input type="file" accept="image/*"
              @change="e => form.featured_image = (e.target as HTMLInputElement)?.files?.[0] ?? null" />
            <div v-if="form.errors.featured_image" class="text-red-600 text-sm mt-1">{{ form.errors.featured_image }}
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Image Caption (optional)</label>
            <input v-model="form.image_caption" type="text" class="w-full border rounded px-3 py-2" />
          </div>
        </div>

        <!-- Excerpt -->
        <div>
          <label class="block text-sm font-medium mb-1">Short Excerpt (optional)</label>
          <textarea v-model="form.excerpt" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <!-- Body (rich text) -->
        <div>
          <label class="block text-sm font-medium mb-2">News Body</label>
          <QuillEditor v-model:content="form.content" content-type="html" toolbar="full" />
          <div v-if="form.errors.content" class="text-red-600 text-sm mt-1">{{ form.errors.content }}</div>
        </div>

        <!-- Quote (optional) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Quote Text (optional)</label>
            <textarea v-model="form.quote_text" rows="2" class="w-full border rounded px-3 py-2"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Quote Author (optional)</label>
            <input v-model="form.quote_author" type="text" class="w-full border rounded px-3 py-2" />
          </div>
        </div>

        <!-- Key Highlights -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="block text-sm font-medium">Key Highlights (optional)</label>
            <button type="button" @click="addHighlight" class="text-green-700 text-sm">+ Add</button>
          </div>
          <div class="space-y-2">
            <div v-for="(h, i) in form.key_highlights" :key="i" class="grid grid-cols-1 md:grid-cols-3 gap-2">
              <input v-model="h.label" placeholder="Label (e.g., Location)" class="border rounded px-3 py-2" />
              <input v-model="h.value" placeholder="Value (e.g., 8 Divisions)"
                class="border rounded px-3 py-2 md:col-span-2" />
              <button type="button" @click="removeHighlight(i)" class="text-red-600 text-sm">Remove</button>
            </div>
          </div>
        </div>

        <!-- SEO -->
        <div>
          <label class="block text-sm font-medium mb-1">Meta Description (optional)</label>
          <input v-model="form.meta_description" class="w-full border rounded px-3 py-2" />
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3">
          <Link :href="route('admin.news.index')" class="px-4 py-2 border rounded">Cancel</Link>
          <button :disabled="form.processing" class="px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">
            {{ form.processing ? 'Saving…' : 'Publish / Save' }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
