<template>
    <section class="py-20 px-[10%]">
        <h2 class="text-4xl font-bold text-neutral-900 flex items-center mb-12">
            <span class="h-8.5 w-1.5 bg-green-700 mr-5 inline-block"></span> Team Envolead
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 justify-items-center">
            <Link v-for="member in members" :key="member.id" :href="`/team/${member.slug}`"
                class="bg-green-800 shadow rounded-md border border-green-800 text-center w-[250px] p-2 block hover:shadow-lg transition">
            <img :src="member.photo" class="w-full h-64 object-cover rounded-md" />
            <div class="bg-green-800 text-white py-4">
                <h3 class="font-bold text-2xl">{{ member.name }}</h3>
                <p class="text-base">{{ member.designation }}</p>
            </div>
            </Link>
        </div>
    </section>
    <a href="/team/morshedul-bari">Test Link</a>

</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ members: Array })
</script>
