<template>
  <q-page class="row justify-center items-center">
    <div class="column">
      <div class="row justify-center">
        <h5 class="text-h5 q-my-md">Login to My App</h5>
      </div>
      <div class="row">
        <q-form class="q-gutter-md" @submit.prevent="login">
          <q-card square bordered class="q-pa-lg shadow-1">
            <q-card-section>
              <q-input ref="emailInput" autofocus square v-model.trim="form.email" label="Email" lazy-rules
                :error="!!form.errors.email" :error-message="form.errors.email"
                :rules="[(val) => validateEmail(val) || 'Must be a valid email.']" />
              <q-input square v-model="form.password" type="password" label="Password" :error="!!form.errors.password"
                :error-message="form.errors.password" lazy-rules
                :rules="[(val) => (val && val.length > 0) || 'Please enter password',]" />
              <q-checkbox class="q-mt-sm q-pl-none" style="margin-left: -10px;" v-model="form.remember"
                label="Remember me" />
            </q-card-section>
            <q-card-actions>
              <q-btn type="submit" color="primary" class="full-width" label="Login" />
            </q-card-actions>
            <q-card-section class="text-center q-pa-none q-mt-md">
              <p class="text-grey-6">
                Not reigistered?
                <Link href="/register">Create an Account</Link>
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

let emailInput = ref(null);
let form = useForm({
  email: "",
  password: "",
  remember: false,
});

const login = () => {
  form.clearErrors();
  form.post("/login", {
    preserveScroll: true,
    onError: () => {
      emailInput.value.focus();
    }
  });
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
