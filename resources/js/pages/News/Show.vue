<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';
import { Head, Link } from '@inertiajs/vue3';
import { MoveLeft } from 'lucide-vue-next';

const props = defineProps<{ news: any }>();
const dateLabel = new Date(props.news.published_at).toLocaleString('en-US', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
});
</script>

<template>
    <Navbar />

    <Head :title="props.news.title">
        <meta name="description" :content="props.news.meta_description || props.news.excerpt" />
    </Head>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <Link :href="route('news.index')" class="text-green-700 flex items-center">
        <MoveLeft width="20" height="20" stroke-width="1.5" class="mr-2" />
        Back to News
        </Link>

        <h1 class="mt-3 text-3xl md:text-4xl font-extrabold text-green-800">
            {{ props.news.title }}
        </h1>
        <p class="text-sm text-gray-500 mt-2">{{ dateLabel }}</p>

        <figure v-if="props.news.featured_image" class="mt-6">
            <img :src="`/storage/${props.news.featured_image}`" :alt="props.news.title" class="w-full rounded-md" />
            <figcaption v-if="props.news.image_caption" class="text-sm text-gray-500 mt-2">
                {{ props.news.image_caption }}
            </figcaption>
        </figure>

        <!-- ✅ Rich Content styled with Tailwind Typography -->
        <div class="prose prose-green max-w-none mt-8" v-html="props.news.content"></div>

        <!-- Quote stays separate -->
        <blockquote v-if="props.news.quote_text" class="border-l-4 border-green-700 pl-4 italic text-gray-800 mt-8">
            “{{ props.news.quote_text }}”
            <footer v-if="props.news.quote_author" class="not-italic font-medium mt-2">
                — {{ props.news.quote_author }}
            </footer>
        </blockquote>

        <!-- Highlights stay separate -->
        <div v-if="props.news.key_highlights?.length" class="mt-10">
            <h3 class="text-xl font-semibold mb-3">Key Highlights:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div v-for="(h, i) in props.news.key_highlights" :key="i" class="bg-gray-50 p-4 rounded">
                    <div class="text-gray-500 text-sm">{{ h.label }}</div>
                    <div class="text-gray-900 font-semibold text-lg">{{ h.value }}</div>
                </div>
            </div>
        </div>
    </div>

    <Footer />
</template>
