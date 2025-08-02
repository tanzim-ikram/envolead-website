<template>
    <section class="bg-[#e6f4ea] py-20 px-[10%]">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold flex items-bottom text-neutral-900">
                <span class="h-8.5 w-1.5 bg-green-700 mr-5"></span> Projects
            </h1>
        </div>

        <div class="flex flex-wrap justify-center gap-8">
            <div v-for="(project, index) in displayedProjects" :key="index"
                class="bg-white p-8 rounded-lg shadow hover:shadow-md text-center flex flex-col justify-between h-[350px] w-[280px]">
                <div>
                    <img :src="project.icon" alt="Icon" class="mx-auto h-16 mb-4" />
                    <h3 class="font-bold text-xl text-green-800">{{ project.project_name }}</h3>
                    <p class="text-base text-neutral-700 mt-2">
                        {{ project.short_description }}
                    </p>
                </div>
                <a href="#"
                    class="text-green-800 font-semibold text-sm mt-4 flex justify-center items-center hover:underline">
                    View project details
                    <Icon icon="tabler:arrow-right" width="16" height="16" class="ml-1" />
                </a>
            </div>
        </div>

        <div class="flex justify-center mt-16">
            <button @click="toggleShowAll"
                class="bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded font-semibold transition flex justify-center items-center text-base">
                {{ showAll ? "Show less projects" : "Show more projects" }}
                <Icon icon="tabler:arrow-right" width="24" height="24" class="ml-2" />
            </button>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Icon } from "@iconify/vue";

const props = defineProps({
    projects: Array
})

const showAll = ref(false)

// Filter only top-level projects (parent_id === null)
const topLevelProjects = computed(() =>
    props.projects.filter(project => project.parent_id === null)
)

const displayedProjects = computed(() => {
    const real = topLevelProjects.value
    const dummyCount = showAll.value ? 0 : Math.max(0, 8 - real.length)

    const dummyProjects = Array.from({ length: dummyCount }, (_, i) => ({
        project_name: `Sample Project ${i + 1}`,
        short_description: 'This is a dummy project description.',
        icon: '/images/envolead-icon.png'
    }))

    const combined = [...real, ...dummyProjects]
    return showAll.value ? combined : combined.slice(0, 8)
})

function toggleShowAll() {
    showAll.value = !showAll.value
}
</script>
