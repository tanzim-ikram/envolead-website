<template>
    <section class="py-20 px-[10%]">
        <h2 class="text-4xl font-bold text-neutral-900 flex items-center mb-16">
            <span class="h-8.5 w-1.5 bg-green-700 mr-5 inline-block"></span> Team Envolead
        </h2>

        <div class="flex flex-wrap justify-center gap-20">
            <Link v-for="member in members" :key="member.id" :href="`/team/${member.slug}`"
                class="bg-green-800 shadow rounded-md border border-green-800 text-center w-[300px] p-2 block hover:shadow-lg transition">
            <img :src="member.photo" class="w-full h-65 object-cover rounded-md" />
            <div class="bg-green-800 text-white py-4">
                <h3 class="font-bold text-2xl">{{ member.name }}</h3>
                <p v-for="(title, idx) in member.designation.split(';')" :key="idx" class="text-base">
                    {{ title.trim() }}
                </p>
            </div>
            </Link>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ members: Array })
</script>
