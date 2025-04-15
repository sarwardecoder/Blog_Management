import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
// import('@/Pages/User/*.vue')
// import('@/Pages/Auth/*.vue')
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'

// createInertiaApp({
//   resolve: name => require(`./Pages/${name}.vue`),
//   setup({ el, App, props, plugin }) {
//     createApp({ render: () => h(App, props) })
//       .use(plugin)
//       .mount(el)
//   },
// })

createInertiaApp({
  resolve: name => import(`./Pages/${name}.vue`),  // correct dynamic import
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el);
  },
});