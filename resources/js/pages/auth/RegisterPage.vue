<template>
  <q-page class="row justify-center items-center">
    <div class="column">
      <div class="row justify-center">
        <h5 class="text-h5 q-my-md">Register</h5>
      </div>
      <div class="row">
        <q-form class="q-gutter-md" @submit.prevent="register">
          <q-card square bordered class="q-pa-lg shadow-1">
            <q-card-section>
              <q-input autofocus class="q-mb-lg" square v-model="form.name" label="Name" lazy-rules
                :error="!!form.errors.name" :error-message="form.errors.name" :rules="[
                  (val) => (val && val.length > 0) || 'Please enter your name.',
                ]" />
              <q-input square v-model="form.email" type="email" label="Email Address" lazy-rules
                :error="!!form.errors.email" :error-message="form.errors.email"
                :rules="[(val) => validateEmail(val) || 'Must be a valid email.']" />
              <q-input square v-model="form.password" type="password" label="Password" lazy-rules
                :error="!!form.errors.password" :error-message="form.errors.password"
                :rules="[(val) => val.length >= 6 || 'Password must be at least 6 character.']" />
              <q-input square v-model="form.password_confirmation" type="password" label="Confirm Password" lazy-rules
                :error="!!form.errors.password_confirmation" :error-message="form.errors.password_confirmation" :rules="[
                  (val) => (val && val.length > 0) || 'Please confirm your password.',
                  () => validatePasswordConfirmation() || 'Password confirmation did not match.'
                ]" />
            </q-card-section>
            <q-card-actions class="q-px-md">
              <q-btn type="submit" color="primary" class="full-width" label="Register" />
            </q-card-actions>
            <q-card-section class="text-center q-pa-none">
              <p class="text-grey-6">
                Already reigistered?
                <Link href="/login">Login</Link>
              </p>
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { useForm } from "@inertiajs/vue3";

defineOptions({
  layout: AuthLayout,
});

let form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const register = () => {
  form.clearErrors();
  form.post("/register", {
    preserveScroll: true
  });
};

function validateEmail(email) {
  return /[a-z0-9]+@[a-z]+\.[a-z]{2,3}/.test(email);
}

function validatePasswordConfirmation() {
  return form.password == form.password_confirmation;
}
</script>

<style>
.q-card {
  width: 360px;
}
</style>
