import { createRouter, createWebHistory } from 'vue-router';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import Home from '@/views/user/Home.vue';
import Shop from '@/views/user/Shop.vue';
import Login from '@/views/user/Login.vue';
import Wishlist from '@/views/user/Wishlist.vue';
import Contact from '@/views/user/Contact.vue';
import ProductDetail from '@/views/user/ProductDetail.vue';
import ChatSystem from '@/components/chat/ChatSystem.vue';

const routes = [
    {
        path: '/',
        name: 'Public',
        component: DefaultLayout,
        redirect: '/',
        children: [
            {
                path: '/',
                name: 'Home',
                component: Home,
            },
            {
                path: '/shop',
                name: 'Shop',
                component: Shop,
            },
            {
                path: '/productdetails',
                name: 'ProductDetails',
                component: ProductDetail,
            },
            {
                path: '/wishlist',
                name: 'Wishlist',
                component: Wishlist,
            },
            {
                path: '/contact',
                name: 'Contact',
                component: Contact,
            },
            {
                path: '/chat',
                name: 'Chat',
                component: ChatSystem,
            },
        ],
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
