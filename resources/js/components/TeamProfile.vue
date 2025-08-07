<template>
    <section class="py-20 px-[10%] bg-white">
        <!-- Heading -->
        <div class="mb-16">
            <h2 class="text-4xl font-bold flex items-center text-neutral-900">
                <span class="h-8.5 w-1.5 bg-green-700 mr-5 inline-block"></span>
                Team Envolead
            </h2>
        </div>

        <!-- Main Layout -->
        <div class="flex flex-col px-25 gap-16">
            <!-- Profile Section -->
            <div class="w-full flex flex-row items-center gap-16">
                <div class="bg-white shadow-lg rounded-lg p-2">
                    <img :src="member.photo" alt="Profile" class="w-[220px] h-auto rounded shadow mx-auto" />
                </div>

                <div>
                    <h3 class="text-3xl font-bold text-green-700">{{ member.name }}</h3>
                    <p class="text-lg font-medium text-neutral-700">{{ member.designation }}</p>

                    <!-- Social Links -->
                    <div class="flex justify-start gap-4 mt-6">
                        <a v-if="member.email" :href="`mailto:${member.email}`"
                            class="bg-green-700 hover:bg-green-800 text-white p-2 rounded-full">
                            <Icon icon="ic:baseline-email" width="24" height="24" />
                        </a>
                        <a v-if="member.linkedin" :href="member.linkedin" target="_blank"
                            class="bg-green-700 hover:bg-green-800 text-white p-2 rounded-full">
                            <Icon icon="ri:linkedin-fill" width="24" height="24" />
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bio Section -->
            <div class="w-full text-justify text-neutral-700 leading-relaxed">
                <p>
                    {{ member.bio }}
                </p>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Icon } from '@iconify/vue'
defineProps({ member: Object })
</script>
