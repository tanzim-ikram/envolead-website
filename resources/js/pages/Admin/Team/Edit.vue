<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({ member: Object });

const form = useForm({
    name: props.member.name,
    designation: props.member.designation,
    email: props.member.email,
    linkedin: props.member.linkedin,
    photo: props.member.photo,
    bio: props.member.bio,
});

const submit = () => {
    form.put(route('admin.team.update', props.member.id));
};
</script>

<template>

    <Head title="Edit Member" />
    <AppLayout>
        <div class="p-6 max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Edit {{ props.member.name }}</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block font-semibold">Name</label>
                    <input v-model="form.name" type="text"
                        class="mt-1 block w-full rounded border border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />

                    <!-- <input v-model="form.name" class="input" type="text" /> -->
                </div>
                <div>
                    <label class="block font-semibold">Designation</label>
                    <!-- <input v-model="form.designation" class="input" type="text" /> -->
                    <input v-model="form.name" type="text"
                        class="mt-1 block w-full rounded border border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />

                </div>
                <div>
                    <label class="block font-semibold">Email</label>
                    <!-- <input v-model="form.email" class="input" type="email" /> -->
                    <input v-model="form.name" type="email"
                        class="mt-1 block w-full rounded border border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />

                </div>
                <div>
                    <label class="block font-semibold">LinkedIn</label>
                    <!-- <input v-model="form.linkedin" class="input" type="text" /> -->
                    <input v-model="form.name" type="text"
                        class="mt-1 block w-full rounded border border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />

                </div>
                <div>
                    <label class="block font-semibold">Photo URL</label>
                    <!-- <input v-model="form.photo" class="input" type="text" /> -->
                    <input v-model="form.name" type="text"
                        class="mt-1 block w-full rounded border border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" />

                </div>
                <div>
                    <label class="block font-semibold">Bio</label>
                    <textarea v-model="form.bio" rows="6" class="input w-full"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded">Update</button>
            </form>
        </div>
    </AppLayout>
</template>

<!-- <style scoped>
.input {
    @apply mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500;
}
</style> -->
