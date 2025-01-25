import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import {InertiaProgress} from '@inertiajs/progress';
import {Link} from '@inertiajs/inertia-vue3';
import PrimeVue from 'primevue/config';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    const page  = pages[`./Pages/${name}.vue`]
    if(!page){
      console.log(`this page component isnt found ${name}`);
    }

    return page;
  },

  setup({ el, App, props, plugin }) {
   const app = createApp({ render: () => h(App, props) })
   app.component('Link', Link);
      app.use(plugin)
      .use(Toast)
      .use(PrimeVue)
      .mount(el)
  },
})

InertiaProgress.init({
  color: '#ffe000',
  showSpinner: true,
})