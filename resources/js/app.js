import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress'
import { Quasar, Notify } from 'quasar'
import Layout from '@/Shared/Layout.vue'

import '@quasar/extras/material-icons/material-icons.css'
import 'quasar/src/css/index.sass'

InertiaProgress.init()

createInertiaApp({
    resolve: async (name) => {
        let page = (await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))).default
        page.layout = page.layout || Layout
        return page
    },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, {
        plugins: { Notify },
        // config: {
        //     brand: {
        //         primary: '#75151E',
        //       },
        // }
        })
      .mount(el)
  },
});
