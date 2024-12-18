import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Home from './views/Home.vue'
import Shop from './views/Shop.vue'

const routes = [
    {path: "/", component: Home},
    {path: "/shop", component: Shop}
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

createApp(App).use(router).mount('#app')
