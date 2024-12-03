import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import VueCookies from 'vue-cookies';

const app = createApp(App)

app.use(router)
app.use(Toast)
app.use(VueCookies)

app.mount('#app')
