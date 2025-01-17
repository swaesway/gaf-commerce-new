import { createRouter, createWebHistory } from "vue-router";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import Home from "@/views/user/Home.vue";
import Shop from "@/views/user/Shop.vue";
import Login from "@/views/user/Login.vue";
import Wishlist from "@/views/user/Wishlist.vue";
import Contact from "@/views/user/Contact.vue";
import ProductDetail from "@/views/user/ProductDetail.vue";
import ChatSystem from "@/components/chat/ChatSystem.vue";
import Verify from "@/views/user/Verify.vue";

import { userStore } from "@/stores/user";
import { createPinia } from "pinia";

const pinia = createPinia();

const routes = [
  {
    path: "/",
    name: "Public",
    component: DefaultLayout,
    redirect: "/",
    children: [
      {
        path: "/",
        name: "Home",
        component: Home,
      },
      {
        path: "/shop",
        name: "Shop",
        component: Shop,
      },
      {
        path: "/product-details/:id",
        name: "ProductDetails",
        component: ProductDetail,
      },
      {
        path: "/wishlist",
        name: "Wishlist",
        component: Wishlist,
      },
      {
        path: "/contact",
        name: "Contact",
        component: Contact,
      },
      {
        path: "/chat",
        name: "Chat",
        component: ChatSystem,
      },
    ],
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
    beforeEnter: (to, from, next) => {
      const { user } = userStore();

      if (to.path === "/login" && user.isAuthenticated) {
        return next({ name: "Home" });
      }

      next();
    },
  },
  {
    path: "/verify",
    name: "verify",
    component: Verify,
    beforeEnter: (to, from, next) => {
      const { user } = userStore();

      if (to.path === "/verify" && user.isAuthenticated) {
        return next({ name: "Home" });
      }

      next();
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const { user } = userStore();

  if (to.path !== "/login" && to.path !== "/verify" && !user.isAuthenticated) {
    return next({ name: "Login" || "verify" });
  }
  next();
});

export default router;
