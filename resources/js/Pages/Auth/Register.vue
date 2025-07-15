<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { VApp, VMain, VContainer, VCard, VCardTitle, VCardText, VTextField, VBtn, VSpacer } from 'vuetify/components';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

    <Head title="Register" />
    <VApp>
        <VMain class="d-flex align-center justify-center"
            style="min-height: 100vh; background: linear-gradient(135deg, #f3f4f6, #e5e7eb);">
            <VContainer class="d-flex align-center justify-center" style="height:100vh;">
                <VCard elevation="10" class="mx-auto pa-8" max-width="400" rounded="xl" color="white darken-4">
                    <VCardTitle class="text-h5 font-weight-bold text-center mb-2">
                        Register
                    </VCardTitle>
                    <VCardText>
                        <form @submit.prevent="submit">
                            <VTextField v-model="form.name" label="Name" type="text" required autofocus
                                autocomplete="name" :error-messages="form.errors.name ? [form.errors.name] : []"
                                class="mb-4" />
                            <VTextField v-model="form.email" label="Email" type="email" required autocomplete="username"
                                :error-messages="form.errors.email ? [form.errors.email] : []" class="mb-4" />
                            <VTextField v-model="form.password" label="Password" type="password" required
                                autocomplete="new-password"
                                :error-messages="form.errors.password ? [form.errors.password] : []" class="mb-4" />
                            <VTextField v-model="form.password_confirmation" label="Confirm Password" type="password"
                                required autocomplete="new-password"
                                :error-messages="form.errors.password_confirmation ? [form.errors.password_confirmation] : []"
                                class="mb-4" />
                            <div class="d-flex align-center justify-end mb-2">
                                <Link :href="route('login')" class="text-caption text-decoration-underline me-4">
                                Already registered?
                                </Link>
                                <VBtn color="red darken-2" :loading="form.processing" :disabled="form.processing"
                                    type="submit" size="large">
                                    Register
                                </VBtn>
                            </div>
                            <div class="d-flex justify-center mt-4">
                              <Link href="/">
                                <VBtn color="grey" variant="outlined" size="large">
                                  Back
                                </VBtn>
                              </Link>
                            </div>
                        </form>
                    </VCardText>
                </VCard>
            </VContainer>
        </VMain>
    </VApp>
</template>
