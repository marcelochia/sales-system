import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import '@fortawesome/fontawesome-free'
import { VueMaskFilter } from "v-mask";

const app = createApp(App)

app.use(router)
app.use(VueMaskFilter)

app.mount('#app')
