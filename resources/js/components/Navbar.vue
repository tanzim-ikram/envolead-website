<template>
  <nav class="flex justify-between items-center py-4 px-30 shadow-sm bg-white">
    <!-- Logo -->
    <div class="flex items-center gap-2">
      <img src="/images/Envolead Logo.svg" alt="Envolead Logo" class="h-10" />
      <!-- <span class="font-semibold text-xl tracking-wide text-gray-800">ENVOLEAD</span> -->
    </div>

    <!-- Navigation Links -->
    <ul class="flex space-x-6 font-semibold text-gray-800 text-sm tracking-wide uppercase">
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
</template>

<script setup>
// No logic needed yet
</script>

<style scoped>
/* Optional: You can add custom fonts or tweaks here if needed */
</style>
