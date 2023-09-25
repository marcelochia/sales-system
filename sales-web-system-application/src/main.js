import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import '@fortawesome/fontawesome-free'

const app = createApp(App)

app.use(router)

app.mount('#app')
