<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { VApp, VMain, VContainer, VCard, VCardTitle, VCardText, VBtn, VSpacer, VFooter } from 'vuetify/components';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});

// ...existing code...
</script>

<template>
  <Head title="Welcome" />
  <VApp>
    <VMain class="d-flex align-center justify-center" style="min-height: 100vh; background: linear-gradient(135deg, #f3f4f6, #e5e7eb);">
      <VContainer class="d-flex align-center justify-center" style="height:100vh;">
        <VCard elevation="12" class="mx-auto pa-8" max-width="400" rounded="xl" color="white darken-4">
          <VCardTitle class="text-h4 font-weight-bold text-center mb-2">
            🚀 Task Manager
          </VCardTitle>
          <VCardText class="text-body-2 text-center mb-6">
            Manage your tasks effortlessly. Login or Register to get started!
          </VCardText>
          <div v-if="canLogin">
            <Link :href="route('login')">
              <VBtn
                color="red darken-2"
                class="mb-4"
                block
                size="large"
              >
                Login
              </VBtn>
            </Link>
            <Link v-if="canRegister" :href="route('register')">
              <VBtn
                color="red darken-2"
                variant="outlined"
                block
                size="large"
              >
                Register
              </VBtn>
            </Link>
          </div>
          <VSpacer />
        </VCard>
      </VContainer>
    </VMain>
  </VApp>
</template>
