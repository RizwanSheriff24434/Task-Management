<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />

  <div class="min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center px-4">
    <div class="bg-white dark:bg-zinc-900 shadow-xl rounded-2xl px-8 py-10 max-w-md w-full text-center">
      <!-- Hero Title -->
      <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
        🚀 Task Manager
      </h1>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-8">
        Manage your tasks effortlessly. Login or Register to get started!
      </p>

      <!-- Auth Buttons -->
      <div v-if="canLogin" class="space-y-4">
        <Link
          :href="route('login')"
          class="block w-full px-5 py-2 rounded-lg bg-[#FF2D20] text-white font-semibold hover:bg-red-600 transition"
        >
          Login
        </Link>

        <Link
          v-if="canRegister"
          :href="route('register')"
          class="block w-full px-5 py-2 rounded-lg border border-[#FF2D20] text-[#FF2D20] font-semibold hover:bg-[#FF2D20]/10 transition"
        >
          Register
        </Link>
      </div>

      <!-- Footer -->
      <p class="mt-8 text-xs text-gray-500 dark:text-gray-400">
        Laravel v{{ laravelVersion }} • PHP v{{ phpVersion }}
      </p>
    </div>
  </div>
</template>
