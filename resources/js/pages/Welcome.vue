<script setup lang="ts">
// import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import HeroSection from '@/components/HeroSection.vue';
import AboutSection from '@/components/AboutSection.vue';
import Projects from '@/components/Projects.vue';
import YouthPartners from '@/components/YouthPartners.vue'
import PartnersAndPatrons from '@/components/PartnersAndPatrons.vue';
import Footer from '@/components/Footer.vue';
import ContactSection from '@/components/ContactSection.vue';

const props = defineProps({
  clubPartners: Array,
  projects: Array,
  companyPartners: Array
})
</script>

<template>
  <div>
    <Navbar />
    <HeroSection />
    <AboutSection />
     <Projects :projects="projects" />
     <YouthPartners :clubPartners="clubPartners" />
     <PartnersAndPatrons :partners="props.companyPartners" />
     <ContactSection />
     <Footer />
  </div>

</template>
