<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />
        <q-toolbar-title class="absolute-center"> My App </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer :breakpoint="768" v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header>{{ user.email }}</q-item-label>
        <q-item v-for="item in navItems" :key="item.name" clickable exact
          @click="item.method ? router[item.method](item.path) : router.get(item.path)">
          <q-item-section avatar>
            <q-icon :name="item.icon" />
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ item.label }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <slot></slot>
    </q-page-container>

    <q-footer elevated>
      <q-tabs>
        <q-route-tab exact v-for="item in navItems" :key="item.name" :to="item.path" :name="item.name" :icon="item.icon"
          :label="item.label" />
      </q-tabs>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { defineComponent, onUnmounted, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const user = page.props.auth.user;

defineComponent({
  name: "DefaultLayout",
});

const leftDrawerOpen = ref(false);
const navItems = ref([
  {
    name: "home",
    label: "Home",
    icon: "home",
    path: "/",
  },
  {
    name: "about",
    label: "About",
    icon: "info",
    path: "/about",
  },
  {
    name: "user-list",
    label: "Users",
    icon: "people",
    path: "/user",
  },
  {
    name: "logout",
    label: "Logout",
    icon: "logout",
    path: "/logout",
    method: 'delete'
  },
]);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>

<style>
@media screen and (min-width: 768px) {
  .q-footer {
    display: none;
  }
}
</style>
