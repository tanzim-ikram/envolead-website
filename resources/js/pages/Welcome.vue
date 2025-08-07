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
import TeamList from '@/components/TeamList.vue';

const props = defineProps({
  clubPartners: Array,
  projects: Array,
  companyPartners: Array,
  team: Array
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
     <TeamList :members="team" />
     <ContactSection />
     <Footer />
  </div>

</template>
