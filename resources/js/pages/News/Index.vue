<script setup>
import Footer from '@/components/Footer.vue'
import Navbar from '@/components/Navbar.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
    news: {
        type: Object,
        required: true,
        default: () => ({ data: [], links: [] })
    }
})

const fmtDate = (iso) => {
    if (!iso) return ''
    try {
        return new Date(iso).toLocaleString('en-US', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        })
    } catch (error) {
        return ''
    }
}
</script>

<template>

    <Navbar />

    <Head title="News" />
    <div class="mx-auto px-[10%] py-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold flex items-bottom text-neutral-900">
                <span class="h-8.5 w-1.5 bg-green-700 mr-5"></span> News
            </h2>
        </div>
        <!-- <h1 class="text-3xl font-extrabold text-green-800 mb-6">News</h1> -->

        <div v-if="news?.data && news.data.length > 0" class="space-y-8">
            <article v-for="item in news.data" :key="item.id" class="border-b pb-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                    <!-- Thumbnail -->
                    <div class="md:col-span-3">
                        <Link :href="route('news.show', item.slug)">
                        <div class="w-full h-40 bg-gray-100 overflow-hidden rounded">
                            <img v-if="item.featured_image" :src="`/storage/${item.featured_image}`"
                                :alt="item.title || 'News image'" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        </div>
                        </Link>
                    </div>

                    <!-- Text -->
                    <div class="md:col-span-9">
                        <p class="text-sm text-gray-500 mb-1">{{ fmtDate(item.published_at) }}</p>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <Link :href="route('news.show', item.slug)" class="hover:underline">
                            {{ item.title || 'Untitled' }}
                            </Link>
                        </h2>
                        <p class="text-gray-700 mb-3">{{ item.excerpt || 'No excerpt available.' }}</p>
                        <Link :href="route('news.show', item.slug)"
                            class="inline-flex items-center text-green-700 font-medium hover:underline">
                        Read more <span class="ml-2">→</span>
                        </Link>
                    </div>
                </div>
            </article>
        </div>

        <!-- Empty -->
        <div v-else class="text-center py-16 text-gray-600">
            <div class="mb-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No news published yet</h3>
            <p class="text-gray-500">Check back later for updates.</p>
        </div>

        <!-- Pagination -->
        <div v-if="news?.links && news.links.length > 3" class="mt-10 flex justify-center space-x-2">
            <Link v-for="(l, index) in news.links" :key="index" :href="l.url" v-html="l.label" :class="[
                'px-3 py-2 text-sm border rounded',
                l.active ? 'bg-green-700 text-white border-green-700'
                    : l.url ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
            ]" :disabled="!l.url" />
        </div>
    </div>
    <Footer />
</template>