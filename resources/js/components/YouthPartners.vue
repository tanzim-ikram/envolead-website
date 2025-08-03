<template>
  <section class="py-20 px-[10%]">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold flex items-bottom text-neutral-900">
        <span class="h-8.5 w-1.5 bg-green-700 mr-5"></span> Youth Partners
      </h2>
    </div>

    <!-- Carousel with fixed-width button areas -->
    <div class="flex items-center">
      <!-- Left Button -->
      <div class="w-12 flex justify-center">
        <button @click="prev" class="text-green-700">
          <Icon icon="mingcute:left-line" width="24" height="24" />
        </button>
      </div>

      <!-- Carousel Track -->
      <div class="overflow-hidden flex-1">
        <div
          class="flex transition-transform duration-300 ease-in-out"
          :style="{ transform: `translateX(-${currentIndex * 160}px)` }"
        >
          <div
            v-for="(partner, index) in clubPartners"
            :key="index"
            class="min-w-[150px] flex justify-center items-center px-4 shrink-0"
          >
            <img :src="partner.logo" :alt="partner.partner_name" class="h-15 object-contain" />
          </div>
        </div>
      </div>

      <!-- Right Button -->
      <div class="w-12 flex justify-center">
        <button @click="next" class="text-green-700">
          <Icon icon="mingcute:right-line" width="24" height="24" />
        </button>
      </div>
    </div>
  </section>
</template>


<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
    clubPartners: { type: Array, default: () => [] },
})

const currentIndex = ref(0)
const visibleItems = 5
let autoSlideInterval = null

function next() {
    if (currentIndex.value < props.clubPartners.length - visibleItems) {
        currentIndex.value++
    } else {
        currentIndex.value = 0
    }
}

function prev() {
    if (currentIndex.value > 0) {
        currentIndex.value--
    } else {
        currentIndex.value = props.clubPartners.length - visibleItems
    }
}

function startAutoSlide() {
    stopAutoSlide()
    autoSlideInterval = setInterval(() => {
        next()
    }, 3000) // every 3 seconds
}

function pauseAutoSlide() {
    stopAutoSlide()
}

function stopAutoSlide() {
    if (autoSlideInterval) {
        clearInterval(autoSlideInterval)
        autoSlideInterval = null
    }
}

onMounted(() => {
    startAutoSlide()
})

onBeforeUnmount(() => {
    stopAutoSlide()
})
</script>
