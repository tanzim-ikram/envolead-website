<template>
    <section class="py-20 px-[10%]">
        <h2 class="text-4xl font-bold text-neutral-900 flex items-center mb-12">
            <span class="h-8.5 w-1.5 bg-green-700 mr-5 inline-block"></span> Team Envolead
        </h2>

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Profile Left -->
            <div class="w-full lg:w-1/3 text-center">
                <img :src="member.photo" class="rounded shadow w-[240px] mx-auto" />
                <h3 class="text-2xl font-bold text-green-800 mt-6">{{ member.name }}</h3>
                <p class="text-lg text-gray-700">{{ member.designation }}</p>
                <div class="flex justify-center mt-4 gap-4 text-green-700">
                    <a v-if="member.email" :href="`mailto:${member.email}`">
                        <Icon icon="tabler:mail" width="24" />
                    </a>
                    <a v-if="member.linkedin" :href="member.linkedin" target="_blank">
                        <Icon icon="tabler:brand-linkedin" width="24" />
                    </a>
                </div>
            </div>

            <!-- Bio Right -->
            <div class="w-full lg:w-2/3 text-gray-800 text-justify leading-relaxed">
                <p>{{ member.bio }}</p>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Icon } from '@iconify/vue'
defineProps({ member: Object })
</script>
