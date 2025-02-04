import { createRouter, createWebHistory } from "vue-router";
import { createPinia } from "pinia";

import { onBeforeMount } from "vue";
import axiosInstance from "@/api/axiosInstance";

import VendorLogin from "@/views/vendors/login.vue";
import VendorRegister from "@/views/vendors/register.vue";
import Vendorlayout from "@/components/layouts/vendorlayout.vue";
import VendorDashboard from "@/views/vendors/dashboard.vue";
import Addproduct from "@/views/vendors/addproduct.vue";
import Editproduct from "@/views/vendors/editproduct.vue";
import Viewproduct from "@/views/vendors/viewproducts.vue";
import Profile from "@/views/vendors/profile.vue";
import CallbackView from "@/views/vendors/callback.vue";
import CallbackSignleView from "@/views/vendors/callbackProduct.vue";

import { useVendorStore } from "@/stores/vendor";

const pinia = createPinia();
// '/:pathMatch(.*)*
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    console.log("Scrolling Behavior Triggered:", savedPosition);
    if (savedPosition) {
      return savedPosition;
    }
    return { top: 0, left: 0, behavior: "smooth" };
  },
  routes: [
    {
      path: "/vendor/login",
      name: "vendorLogin",
      component: VendorLogin,
      beforeEnter: (to, from, next) => {
        const { vendor } = useVendorStore(pinia);
        if (to.path === "/vendor/login" && vendor.isAuthenticated) {
          return next({ name: "dashboard" });
        }

        next();
      },
    },
    {
      path: "/vendor/register",
      name: "vendorRegister",
      component: VendorRegister,
      beforeEnter: (to, from, next) => {
        const { vendor } = useVendorStore(pinia);
        if (to.path === "/vendor/register" && vendor.isAuthenticated) {
          return next({ name: "dashboard" });
        }

        next();
      },
    },
    {
      path: "/vendor",
      name: "vendorLayout",
      component: Vendorlayout,
      children: [
        {
          path: "/vendor",
          name: "dashboard",
          component: VendorDashboard,
        },
        {
          path: "addproduct",
          name: "addproduct",
          component: Addproduct,
        },
        {
          path: "editproduct",
          name: "editproduct",
          component: Editproduct,
        },
        {
          path: "callbacks",
          name: "callbacks",
          component: CallbackView,
        },
        {
          path: "callback/:id",
          component: CallbackSignleView,
        },
        {
          path: "viewproducts",
          name: "viewproducts",
          component: Viewproduct,
        },
        {
          path: "profile",
          name: "profile",
          component: Profile,
        },
      ],
    },
  ],
});

router.beforeEach(async (to, from, next) => {
  const { vendor, checkAuthStatus } = useVendorStore();
  if (
    to.path !== "/vendor/register" &&
    to.path !== "/vendor/login" &&
    !vendor.isAuthenticated
  ) {
    return next({ name: "vendorLogin" });
  }

  next();
});

export default router;
