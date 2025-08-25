<template>
    <Navbar />
    <section class="bg-[#e9f1ed] py-20 px-[10%]">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold flex items-bottom text-neutral-900">
                <span class="h-8.5 w-1.5 bg-green-700 mr-5"></span>
                Projects
            </h2>
        </div>

        <div class="flex flex-wrap justify-center gap-8">
            <div v-for="project in displayedProjects" :key="project.id || project.temp_id"
                class="bg-white p-8 rounded-lg shadow hover:shadow-md text-center flex flex-col justify-between h-[350px] w-[280px] transition-all duration-200 hover:-translate-y-1">
                <div>
                    <img :src="project.icon ? `/storage/${project.icon}` : '/images/default-project-icon.png'"
                        :alt="project.project_name + ' icon'" class="mx-auto h-16 mb-4 object-contain" />
                    <h3 class="font-bold text-xl text-green-800">{{ project.project_name }}</h3>
                    <p class="text-base text-neutral-700 mt-2">
                        {{ project.short_description }}
                    </p>
                    <!-- Show children count if has children -->
                    <div v-if="project.children && project.children.length > 0" class="mt-3">
                        <span class="text-sm text-green-600 bg-green-100 px-2 py-1 rounded-full">
                            {{ project.children.length }} {{ project.children.length === 1 ? 'subproject' :
                            'subprojects' }}
                        </span>
                    </div>
                </div>

                <Link v-if="project.slug" :href="route('projects.show', project.slug)"
                    class="text-green-800 font-semibold text-sm mt-4 flex justify-center items-center hover:underline transition-colors">
                {{ project.children && project.children.length > 0 ? 'View subprojects' : 'View project details' }}
                <MoveRight width="20" height="20" stroke-width="1.5" class="ml-2" />
                </Link>

                <!-- For dummy projects -->
                <span v-else class="text-gray-500 font-semibold text-sm mt-4 flex justify-center items-center">
                    Coming soon...
                </span>
            </div>
        </div>

        <div v-if="realProjects.length > 8" class="flex justify-center mt-16">
            <button @click="toggleShowAll"
                class="bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded font-semibold transition flex justify-center items-center text-base">
                {{ showAll ? "Show less projects" : "Show more projects" }}
                <Icon :icon="showAll ? 'tabler:arrow-up' : 'tabler:arrow-right'" width="24" height="24" class="ml-2" />
            </button>
        </div>
    </section>
    <Footer />
</template>

<script setup>
import { ref, computed } from 'vue'
import { Icon } from "@iconify/vue"
import { MoveRight } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

const props = defineProps({
    projects: Array
})

const showAll = ref(false)

// Real projects from database
const realProjects = computed(() => props.projects || [])

const displayedProjects = computed(() => {
    const real = realProjects.value
    return showAll.value ? real : real.slice(0, 8)
})

function toggleShowAll() {
    showAll.value = !showAll.value
}
</script>