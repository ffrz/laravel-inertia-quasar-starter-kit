import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { Dialog, Loading, Notify, Quasar } from "quasar";
import DefaultLayout from "./layouts/DefaultLayout.vue";
import "@quasar/extras/material-icons/material-icons.css";
import "quasar/src/css/index.sass";
import { router } from "@inertiajs/vue3";
import processFlashMessage from "@/helpers/flash";

let flashMessageProcessed = false;

createInertiaApp({
  title: (title) => "My App" + (title ? " - " + title : ""),
  resolve: (name) => {
    const pages = import.meta.glob("./pages/**/*.vue", { eager: true });
    let page = pages[`./pages/${name}.vue`];

    if (page.default.layout !== false) {
      page.default.layout = page.default.layout || DefaultLayout;
    }

    if (!flashMessageProcessed) {
      flashMessageProcessed = true;
      router.on('success', processFlashMessage);
    }

    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, {
        plugins: { Notify, Loading, Dialog },
      })
      .component("Head", Head)
      .component("Link", Link)
      .mount(el);
  },
});
