<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  members: Array
});

const breadcrumbs = computed(() => [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Team', href: '/admin/team' },
]);
</script>

<template>
  <Head title="Team Members" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <h1 class="text-2xl font-semibold mb-6">All Team Members</h1>
      <table class="w-full table-auto border border-gray-200 text-left text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border text-center">Photo</th>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Designation</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="member in members" :key="member.id" class="hover:bg-gray-50">
            <td class="p-2 border w-16">
              <img :src="member.photo" class="h-12 w-12 rounded object-cover" />
            </td>
            <td class="p-2 border">{{ member.name }}</td>
            <td class="p-2 border">
              <div v-for="(title, idx) in member.designation.split(',')" :key="idx">
                {{ title.trim() }}
              </div>
            </td>
            <td class="p-2 border">{{ member.email || '—' }}</td>
            <td class="p-2 border text-center">
              <Link :href="`/team/${member.slug}`" class="text-green-700 font-medium hover:underline">
                View
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>
