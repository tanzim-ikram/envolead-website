<template>
    <section class="bg-[#e9f1ed] py-20 px-[10%]">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold flex items-bottom text-neutral-900">
                <span class="h-8.5 w-1.5 bg-green-700 mr-5"></span> Partners & Patrons
            </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 place-items-center">
            <div v-for="(partner, index) in partners" :key="index"
                class="bg-white border-1 border-neutral-50 p-4 shadow rounded flex justify-center items-center h-28 w-full hover:border-1 hover:border-green-700 transition">
                <img :src="partner.logo" :alt="partner.partner_name" class="max-h-20 max-w-full object-contain" />
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({
    partners: Array,
});
</script>
