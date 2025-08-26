<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ArrowLeft, Pencil, Trash2, Play, Eye } from 'lucide-vue-next'

interface NewsItem {
    id: number
    title: string
    slug: string
    created_at: string
    updated_at: string
    published_at: string | null
    status: string
}

interface PaginatedNews {
    data: NewsItem[]
    links?: Array<{
        label: string
        url: string | null
        active: boolean
    }>
}

const props = defineProps<{
    news: PaginatedNews
}>()

const formatDate = (iso?: string | null) => {
    if (!iso) return 'Not set'
    try {
        return new Date(iso).toLocaleString('en-US', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        })
    } catch (error) {
        return 'Invalid date'
    }
}

const confirmAndDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this news item? This action cannot be undone.')) {
        router.delete(route('admin.news.destroy', id))
    }
}

const publish = (id: number) => {
    router.patch(route('admin.news.toggle-status', id))
}

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'News', href: '/admin/news' },
    { title: 'Manage News', href: '/admin/news' },
]
</script>

<template>

    <Head title="Manage News" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Manage News</h1>
                    <p class="text-gray-600 mt-1">Create, edit, and organize your news</p>
                </div>

                <Link :href="route('admin.news.create')"
                    class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded hover:bg-green-800">
                <ArrowLeft class="w-4 h-4 mr-2" />
                Post News
                </Link>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-800">All News</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border-t border-gray-200">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Title</th>
                                <th class="px-6 py-3">Created</th>
                                <th class="px-6 py-3">Updated</th>
                                <th class="px-6 py-3">Publish Date</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-if="!props.news?.data || props.news.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No news items found.
                                    <Link :href="route('admin.news.create')" class="text-green-700 hover:underline">
                                    Create your first news item</Link>.
                                </td>
                            </tr>
                            <tr v-else v-for="item in props.news.data" :key="item.id"
                                class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ item.title || 'Untitled' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ formatDate(item.created_at) }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ formatDate(item.updated_at) }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ formatDate(item.published_at) }}</td>
                                <td class="px-6 py-4">
                                    <span :class="[
                                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                                        item.status === 'published' ? 'bg-green-100 text-green-800' :
                                            item.status === 'archived' ? 'bg-red-100 text-red-800' :
                                                'bg-gray-100 text-gray-700'
                                    ]">
                                        {{ item.status || 'draft' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <Link :href="route('admin.news.edit', item.id)"
                                            class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200">
                                        <Pencil class="w-4 h-4" />
                                        </Link>
                                        <button v-if="item.status === 'draft'" @click="publish(item.id)"
                                            class="inline-flex items-center p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-md transition-colors duration-200">
                                            <Play class="w-4 h-4" />
                                        </button>
                                        <button @click="confirmAndDelete(item.id)"
                                            class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                        <Link v-if="item.status === 'published' && item.slug"
                                            :href="route('news.show', item.slug)"
                                            class="inline-flex items-center p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded-md transition-colors duration-200"
                                            target="_blank">
                                        <Eye class="w-4 h-4" />
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="props.news?.links && props.news.links.length > 3"
                    class="p-4 border-t border-gray-200 flex justify-center space-x-2">
                    <template v-for="(l, index) in props.news.links" :key="index">
                        <Link v-if="l.url" :href="l.url" v-html="l.label" :class="[
                            'px-3 py-2 text-sm border rounded',
                            l.active ? 'bg-green-700 text-white border-green-700'
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                        ]" />
                        <span v-else v-html="l.label"
                            class="px-3 py-2 text-sm border rounded bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed" />
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>