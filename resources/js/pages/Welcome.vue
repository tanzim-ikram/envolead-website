<script setup lang="ts">
// import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import HeroSection from '@/components/HeroSection.vue';
import AboutSection from '@/components/AboutSection.vue';
import Projects from '@/components/Projects.vue';
import YouthPartners from '@/components/YouthPartners.vue'

const props = defineProps({
  clubPartners: Array,
  projects: Array
})
</script>

<template>
  <div>
    <Navbar />
    <HeroSection />
    <AboutSection />
     <Projects :projects="projects" />
     <YouthPartners :clubPartners="clubPartners" />
  </div>

</template>
