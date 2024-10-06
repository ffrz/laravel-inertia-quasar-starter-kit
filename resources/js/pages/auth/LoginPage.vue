<template>
  <q-page class="row justify-center items-center">
    <div class="column">
      <div class="row justify-center">
        <h5 class="text-h5 q-my-md">Login to My App</h5>
      </div>
      <div class="row">
        <q-form ref="formEl" class="q-gutter-md" @submit.prevent="login">
          <q-card square bordered class="q-pa-lg shadow-1">
            <q-card-section>
              <q-input
                class="q-mb-lg"
                square
                filled
                v-model="form.email"
                label="Email"
                lazy-rules
                :error="form.errors.email && !isValid"
                :rules="[
                  (val) => validateEmail(val) || 'Must be a valid email.',
                ]"
              >
                <template #error>
                  <div id="error-alert">{{ form.errors.email }}</div>
                </template>
              </q-input>
              <q-input

                square
                filled
                v-model="form.password"
                type="password"
                label="Password"
                :error="form.errors.password && !isValid"
                lazy-rules
                :rules="[
                  (val) => (val && val.length > 0) || 'Please enter password',
                ]"
              >
                <template #error>
                  <div id="error-alert">{{ form.errors.password }}</div>
                </template>
              </q-input>
            </q-card-section>
            <q-card-actions class="q-px-md">
              <q-btn
                type="submit"
                unelevated
                color="primary"
                size="lg"
                class="full-width"
                label="Login"
              />
            </q-card-actions>
            <q-card-section class="text-center q-pa-none">
              <p class="text-grey-6">
                Not reigistered? <Link href="/register">Create an Account</Link>
              </p>
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthLayout from "../../layouts/AuthLayout.vue";

defineOptions({
  layout: AuthLayout,
});

let formEl = ref(null);
let form = useForm({
  email: "",
  password: "",
  remember: false,
});

const login = () => {
  form.clearErrors();
  form.post("/login");
};

function validateEmail(email) {
  return /[a-z0-9]+@[a-z]+\.[a-z]{2,3}/.test(email);
}
</script>

<style>
.q-card {
  width: 360px;
}
</style>
