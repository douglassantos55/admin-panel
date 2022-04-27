import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import Layout from './Pages/Layout.vue'
import Maska from 'maska'
import { ZiggyVue } from 'ziggy'

InertiaProgress.init()

createInertiaApp({
  resolve: name => {
      const page = require(`./Pages/${name}`).default
      page.layout = page.layout || Layout
      return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Maska)
      .use(ZiggyVue)
      .mount(el)
  },
})
