<script setup lang="ts">
// import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import HeroSection from '@/components/HeroSection.vue';
import AboutSection from '@/components/AboutSection.vue';
import Projects from '@/components/Projects.vue';
defineProps({
  projects: Array
})
</script>

<template>
  <div>
    <Navbar />
    <HeroSection />
    <AboutSection />
     <Projects :projects="projects" />
  </div>

</template>
