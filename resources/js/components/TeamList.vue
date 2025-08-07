<template>
    <section class="py-20 px-[10%]">
        <h2 class="text-4xl font-bold text-neutral-900 flex items-center mb-12">
            <span class="h-8.5 w-1.5 bg-green-700 mr-5 inline-block"></span> Team Envolead
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 justify-items-center">
            <div v-for="member in members" :key="member.id"
                class="bg-white shadow rounded-lg border border-green-800 text-center w-[220px]">
                <router-link :href="`/team/${member.slug}`" class="block">
                    <img :src="member.photo" class="w-full h-64 object-cover rounded-t" />
                    <div class="bg-green-800 text-white py-4">
                        <h3 class="font-bold text-lg">{{ member.name }}</h3>
                        <p class="text-sm">{{ member.designation }}</p>
                    </div>
                </router-link>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({ members: Array })
</script>
