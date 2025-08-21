<template>
  <footer class="bg-white border-t border-gray-300">
    <div class="max-w-7xl mx-auto px-[10%] md:px-12 grid grid-cols-1 md:grid-cols-4 gap-10 py-28">
      <!-- Logo & Socials -->
      <div>
        <img src="/images/Envolead Logo.png" alt="Envolead Logo" class="w-36 mb-6" />
        <div class="flex gap-5">
          <a href="#"><i class="fab fa-facebook text-2xl text-green-800 hover:text-green-900"></i></a>
          <a href="#"><i class="fab fa-instagram text-2xl text-green-800 hover:text-green-900"></i></a>
          <a href="#"><i class="fab fa-linkedin text-2xl text-green-800 hover:text-green-900"></i></a>
          <a href="#"><i class="fab fa-youtube text-2xl text-green-800 hover:text-green-900"></i></a>
        </div>
      </div>

      <!-- Contacts -->
      <div>
        <h3 class="font-bold text-lg mb-2">Contacts</h3>
        <p class="text-sm text-neutral-700">
          Ventura Iconia, Level 3, Holding 37, <br />
          Road 11, Banani, Block H, Dhaka 1213, Bangladesh
        </p>
        <p class="text-sm mt-2 text-neutral-700">Phone: +88 017 3071 3866</p>
        <p class="text-sm text-neutral-700">Email: <a href="mailto:info@envolead.com" class="text-green-800">info@envolead.com</a></p>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="font-bold text-lg mb-2">Quick Links</h3>
        <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-sm text-neutral-700">
          <a href="/" class="hover:underline">Home</a>
          <a href="#" class="hover:underline">Partners</a>
          <a href="#" class="hover:underline">About Us</a>
          <a href="#" class="hover:underline">Team</a>
          <a href="#" class="hover:underline">Projects</a>
          <a href="#" class="hover:underline">Contact</a>
        </div>
      </div>

      <!-- Newsletter -->
      <div>
        <h3 class="font-bold text-lg mb-2">Join Our Newsletter</h3>
        <input
          v-model="email"
          type="email"
          placeholder="Your Email Address"
          class="w-full px-4 py-2 border border-green-800 rounded-md focus:outline-none"
        />
        <button
          @click="subscribe"
          class="mt-3 w-full bg-green-800 hover:bg-green-900 text-white py-2 rounded-md font-semibold flex justify-center items-center gap-2"
        >
          Join 
          <Icon icon="tabler:arrow-right" width="24" height="24" />
        </button>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="bg-green-800 text-white text-sm py-4 px-[10%] flex flex-col md:flex-row justify-between items-center">
      <p>© 2025 Envolead Ltd. | All Rights Reserved</p>
      <div class="flex gap-4 mt-2 md:mt-0">
        <a href="#" class="hover:underline">Privacy Policy</a>
        <span>|</span>
        <a href="#" class="hover:underline">Terms & Conditions</a>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref } from 'vue'
import { Icon } from "@iconify/vue";

const email = ref('')

const subscribe = () => {
  if (!email.value) {
    alert('Please enter a valid email address.')
    return
  }
  // Implement subscription logic here (e.g., API call)
  alert(`Subscribed: ${email.value}`)
  email.value = ''
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
