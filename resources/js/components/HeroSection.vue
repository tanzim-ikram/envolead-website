<template>
    <section class="relative h-[90vh] bg-cover bg-center flex items-center justify-start"
        style="background-image: url('/images/BG.png')">
        <!-- Overlay (optional) -->
        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-2xl pl-[10%] mt-45">
            <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-6">
                Working Together For <br />
                Sustainable Solutions
            </h1>
            <a href="#about"
                class="inline-flex items-center px-6 py-3 bg-green-800 text-white font-semibold text-base rounded-md shadow hover:bg-green-900 transition">
                Learn More
                <!-- <MoveRight width="24" height="24" stroke-width="2.25" class="ml-2" /> -->
                <Icon icon="tabler:arrow-right" width="24" height="24" class="ml-2" />
            </a>
        </div>
    </section>
</template>

<script setup>
import { Icon } from "@iconify/vue";
import { MoveRight } from 'lucide-vue-next';
</script>


<style scoped>
/* Optional: Fine-tune positioning for mobile */
</style>
