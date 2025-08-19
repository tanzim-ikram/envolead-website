<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

const props = defineProps<{ news: any }>()

const form = useForm({
    title: props.news.title ?? '',
    excerpt: props.news.excerpt ?? '',
    content: props.news.content ?? '',
    featured_image: null as File | null,
    image_caption: props.news.image_caption ?? '',
    quote_text: props.news.quote_text ?? '',
    quote_author: props.news.quote_author ?? '',
    key_highlights: props.news.key_highlights ?? [],
    published_at: (props.news.published_at || '').slice(0, 10),
    status: props.news.status ?? 'draft',
    meta_description: props.news.meta_description ?? '',
})

const addHighlight = () => form.key_highlights.push({ label: '', value: '' })
const removeHighlight = (i: number) => form.key_highlights.splice(i, 1)

const submit = () => {
    form.post(route('admin.news.update', props.news.id), {
        method: 'put',
        forceFormData: true,
        preserveScroll: true,
    })
}
</script>

<template>

    <Head :title="`Edit: ${props.news.title}`" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Edit News</h1>
                <Link :href="route('admin.news.index')" class="text-green-700 hover:underline">Back</Link>
            </div>

            <form @submit.prevent="submit" class="bg-white border rounded-lg p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Title</label>
                    <input v-model="form.title" class="w-full border rounded px-3 py-2" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Published Date</label>
                        <input v-model="form.published_at" type="date" class="w-full border rounded px-3 py-2" />
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block text-sm font-medium mb-1">Current Featured Image</label>
                        <div class="h-32 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                            <img v-if="props.news.featured_image" :src="`/storage/${props.news.featured_image}`"
                                class="h-full object-cover" />
                            <span v-else class="text-gray-500 text-sm">No image</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Replace Image</label>
                        <input type="file" accept="image/*"
                            @change="e => form.featured_image = (e.target as HTMLInputElement)?.files?.[0] ?? null" />
                        <label class="block text-sm font-medium mt-3 mb-1">Image Caption</label>
                        <input v-model="form.image_caption" class="w-full border rounded px-3 py-2" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Excerpt</label>
                    <textarea v-model="form.excerpt" rows="2" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">News Body</label>
                    <QuillEditor v-model:content="form.content" content-type="html" toolbar="full" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Quote Text</label>
                        <textarea v-model="form.quote_text" rows="2" class="w-full border rounded px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Quote Author</label>
                        <input v-model="form.quote_author" class="w-full border rounded px-3 py-2" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium">Key Highlights</label>
                        <button type="button" @click="addHighlight" class="text-green-700 text-sm">+ Add</button>
                    </div>
                    <div class="space-y-2">
                        <div v-for="(h, i) in form.key_highlights" :key="i"
                            class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <input v-model="h.label" placeholder="Label" class="border rounded px-3 py-2" />
                            <input v-model="h.value" placeholder="Value"
                                class="border rounded px-3 py-2 md:col-span-2" />
                            <button type="button" @click="removeHighlight(i)"
                                class="text-red-600 text-sm">Remove</button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Meta Description</label>
                    <input v-model="form.meta_description" class="w-full border rounded px-3 py-2" />
                </div>

                <div class="flex justify-end gap-3">
                    <Link :href="route('admin.news.index')" class="px-4 py-2 border rounded">Cancel</Link>
                    <button :disabled="form.processing" class="px-4 py-2 bg-green-700 text-white rounded">
                        {{ form.processing ? 'Saving…' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
