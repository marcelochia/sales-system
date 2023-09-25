import { ref } from "vue";

export const userCounterStore = defineStore('auth', () => {
    const token = ref(localStorage.getItem('access_token'));
    const userName = ref(localStorage.getItem('user_name'));
    const isAdmin = ref(localStorage.getItem('is_admin'));
});