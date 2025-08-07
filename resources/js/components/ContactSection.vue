<template>
    <section class="bg-[#e9f1ed] py-20 px-[10%] min-h-screen">
        <!-- Heading -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold flex items-bottom text-neutral-900">
                <span class="h-8.5 w-1.5 bg-green-700 mr-5"></span> Contact
            </h2>
        </div>
        <div class="max-w-2xl mx-auto">

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Full Name -->
                <div>
                    <label for="name" class="block mb-1 text-base font-medium text-gray-800">Full Name:</label>
                    <input v-model="form.name" type="text" id="name" placeholder="Rahat Hasan"
                        class="w-full text-base border-2 bg-white/50 border-green-700 rounded-md px-4 py-2 focus:outline-green-500" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-1 text-base font-medium text-gray-800">Email Address:</label>
                    <input v-model="form.email" type="email" id="email" placeholder="rahathasan@email.com"
                        class="w-full text-base border-2 bg-white/50 border-green-700 rounded-md px-4 py-2 focus:outline-green-500" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block mb-1 text-base font-medium text-gray-800">Phone Number:</label>
                    <input v-model="form.phone" type="tel" id="phone" placeholder="+8801712345678"
                        class="w-full text-base border-2 bg-white/50 border-green-700 rounded-md px-4 py-2 focus:outline-green-500" />
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block mb-1 text-base font-medium text-gray-800">Your Message:</label>
                    <textarea v-model="form.message" id="message" rows="4"
                        placeholder="Hello, there! I hope this message finds you well. I would like to inquire about the types of services you offer."
                        class="w-full text-base border-2 bg-white/50 border-green-700 rounded-md px-4 py-2 focus:outline-green-500"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center mt-8">
                    <button type="submit"
                        class="bg-green-800 hover:bg-green-900 text-white px-6 py-3 rounded-md font-semibold transition flex items-center gap-2">
                        Send message
                        <Icon icon="mingcute:message-4-line" width="24" height="24" />
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue'
import { Icon } from '@iconify/vue'

const form = ref({
    name: '',
    email: '',
    phone: '',
    message: ''
})

const handleSubmit = () => {
    // Your logic (e.g., form validation or API call)
    console.log('Form submitted:', form.value)
    alert('Message sent!')
    // Reset
    form.value = { name: '', email: '', phone: '', message: '' }
}
</script>
