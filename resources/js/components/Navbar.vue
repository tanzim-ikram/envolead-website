<template>
  <div class="sticky top-0 z-50 bg-white shadow-sm">
    <nav class="flex justify-between items-center py-4 px-[5%]">
      <!-- Logo -->
      <div class="flex items-center gap-2">
        <img src="/images/Envolead Logo.svg" alt="Envolead Logo" class="h-12.5" />
      </div>

      <!-- Navigation Links -->
      <ul class="flex space-x-6 font-semibold text-gray-800 text-base tracking-wide uppercase">
        <li><a href="#home" class="hover:text-green-700">Home</a></li>
        <li><a href="#about" class="hover:text-green-700">About</a></li>
        <li><a href="#projects" class="hover:text-green-700">Projects</a></li>
        <li><a href="#partners" class="hover:text-green-700">Partners</a></li>
        <li><a href="#team" class="hover:text-green-700">Team</a></li>
      </ul>

      <!-- Contact Button -->
      <a
        href="#contact"
        class="bg-green-800 text-white px-6 py-2 rounded-md font-bold text-sm tracking-wide uppercase hover:bg-green-900 transition"
      >
        Contact
      </a>
    </nav>
    <hr class="w-full border-gray-300" />
  </div>
</template>

<script setup>
// No logic needed yet
</script>

<style scoped>
/* Optional: Add shadow or transition on scroll */
</style>
