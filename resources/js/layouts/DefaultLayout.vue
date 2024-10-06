<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title class="absolute-center"> My App </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer :breakpoint="768" v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header>Navigation</q-item-label>
        <q-item
          v-for="item in navItems"
          :key="item.name"
          clickable
          exact
          @click="router.get(item.path)"
        >
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
        <q-route-tab
          exact
          v-for="item in navItems"
          :key="item.name"
          :to="item.path"
          :name="item.name"
          :icon="item.icon"
          :label="item.label"
        />
      </q-tabs>
    </q-footer>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { router } from "@inertiajs/vue3";

export default defineComponent({
  name: "DefaultLayout",
  components: {},
  setup() {
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
        name: "logout",
        label: "Logout",
        icon: "logout",
        path: "/logout",
      },
    ]);

    return {
      router,
      leftDrawerOpen,
      navItems,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
});
</script>

<style>
@media screen and (min-width: 768px) {
  .q-footer {
    display: none;
  }
}
</style>
