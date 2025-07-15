<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { VApp, VMain, VContainer, VCard, VCardTitle, VCardText, VTextField, VCheckbox, VBtn, VSpacer, VAlert } from 'vuetify/components';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <Head title="Log in" />
  <VApp>
    <VMain class="d-flex align-center justify-center" style="min-height: 100vh; background: linear-gradient(135deg, #f3f4f6, #e5e7eb);">
      <VContainer class="d-flex align-center justify-center" style="height:100vh;">
        <VCard elevation="10" class="mx-auto pa-8" max-width="400" rounded="xl" color="white darken-4">
          <VCardTitle class="text-h5 font-weight-bold text-center mb-2">
            Log in
          </VCardTitle>
          <VCardText>
            <VAlert v-if="status" type="success" class="mb-4">{{ status }}</VAlert>
            <form @submit.prevent="submit">
              <VTextField
                v-model="form.email"
                label="Email"
                type="email"
                required
                autofocus
                autocomplete="username"
                :error-messages="form.errors.email ? [form.errors.email] : []"
                class="mb-4"
              />
              <VTextField
                v-model="form.password"
                label="Password"
                type="password"
                required
                autocomplete="current-password"
                :error-messages="form.errors.password ? [form.errors.password] : []"
                class="mb-4"
              />
              <VCheckbox
                v-model="form.remember"
                label="Remember me"
                class="mb-2"
              />
              <div class="d-flex align-center justify-end mb-2">
                <Link
                  v-if="canResetPassword"
                  :href="route('password.request')"
                  class="text-caption text-decoration-underline me-4"
                >
                  Forgot your password?
                </Link>
                <VBtn
                  color="red darken-2"
                  :loading="form.processing"
                  :disabled="form.processing"
                  type="submit"
                  size="large"
                >
                  Log in
                </VBtn>
              </div>
            </form>
          </VCardText>
        </VCard>
      </VContainer>
    </VMain>
  </VApp>
</template>
