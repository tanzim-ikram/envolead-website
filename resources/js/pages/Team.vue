<script setup lang="ts">
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import TeamProfile from '@/components/TeamProfile.vue';

const props = defineProps({
    member: Object
})

</script>

<template>
    <div>
        <Navbar />
        <TeamProfile :member="member" />
        <Footer />
    </div>
</template>
