<template>
  <section
    class="relative h-[90vh] bg-cover bg-center flex items-center justify-start"
    style="background-image: url('/images/BG.png')"
  >
    <!-- Overlay (optional) -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Content -->
    <div class="relative z-10 max-w-2xl px-[5%]">
      <h1 class="text-white text-4xl md:text-5xl font-extrabold leading-tight mb-6">
        Working Together For<br />
        Sustainable Solutions
      </h1>
      <a
        href="#about"
        class="inline-flex items-center px-6 py-3 bg-green-800 text-white font-semibold text-sm rounded-md shadow hover:bg-green-900 transition"
      >
        Learn More
        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </section>
</template>

<script setup>
// No logic needed for now
</script>

<style scoped>
/* Optional: Fine-tune positioning for mobile */
</style>
