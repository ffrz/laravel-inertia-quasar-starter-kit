import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { Quasar } from "quasar";
import DefaultLayout from "./layouts/DefaultLayout.vue";
import "@quasar/extras/material-icons/material-icons.css";
import "quasar/src/css/index.sass";

createInertiaApp({
  title: (title) => "My App" + (title ? " - " + title : ""),
  resolve: (name) => {
    const pages = import.meta.glob("./pages/**/*.vue", { eager: true });
    let page = pages[`./pages/${name}.vue`];

    if (page.default.layout !== false) {
      page.default.layout = page.default.layout || DefaultLayout;
    }

    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, {
        plugins: {},
      })
      .component("Head", Head)
      .component("Link", Link)
      .mount(el);
  },
});
