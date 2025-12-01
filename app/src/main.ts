import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import piniaPersist from 'pinia-plugin-persistedstate'
import axiosInstance from './plugins/axios' 

// Toastification
import Toast, { type PluginOptions } from 'vue-toastification'
import { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

// Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import 'bootstrap-icons/font/bootstrap-icons.css'

import VueApexCharts from 'vue3-apexcharts' 

const toastOptions: PluginOptions = {
  position: POSITION.TOP_RIGHT,
  timeout: 4000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  showCloseButtonOnHover: false,
  hideProgressBar: false,
  icon: true,
  transition: 'Vue-Toastification__fade',
}

const app = createApp(App)
app.config.globalProperties.$axios = axiosInstance
const pinia = createPinia()
pinia.use(piniaPersist)
app.use(pinia)
app.use(router)
app.use(Toast, toastOptions)
app.use(VueApexCharts)
app.mount('#app')