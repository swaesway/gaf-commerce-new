import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Home from './views/user/Home.vue'
import Shop from './views/user/Shop.vue'
import Login from './views/user/Login.vue'
import Register from './views/user/Register.vue'
import Contact from './views/user/Contact.vue'
import Wishlist from './views/user/Wishlist.vue'

const routes = [
    {path: "/", component: Home},
    {path: "/shop", component: Shop},
    {path: "/login", component: Login},
    {path: "/register", component: Register},
    {path: "/contact", component: Contact},
    {path: "/wishlist", component: Wishlist},
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

createApp(App).use(router).mount('#app')
