<template>
  <q-page class="row justify-center items-center">
    <div class="column">
      <div class="row justify-center">
        <h5 class="text-h5 q-my-md">Register</h5>
      </div>
      <div class="row">
        <q-card square bordered class="q-pa-lg shadow-1">
          <q-card-section>
            <q-form class="q-gutter-md">
              <q-input
                square
                filled
                clearable
                v-model="form.email"
                type="email"
                label="Email Address"
              />
              <q-input
                square
                filled
                clearable
                v-model="form.password"
                type="password"
                label="Password"
              />
              <q-input
                square
                filled
                clearable
                v-model="form.passwordConfirmed"
                type="password"
                label="Confirm Password"
              />
            </q-form>
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn
              unelevated
              color="primary"
              size="lg"
              class="full-width"
              label="Login"
            />
          </q-card-actions>
          <q-card-section class="text-center q-pa-none">
            <p class="text-grey-6">
              Already reigistered? <Link href="/login">Login</Link>
            </p>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref } from "vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { useForm } from "@inertiajs/vue3";

defineOptions({
  layout: AuthLayout,
});

let form = useForm({
  email: '',
  password: '',
  passwordConfirmed: '',
})

</script>

<style>
.q-card {
  width: 360px;
}
</style>
