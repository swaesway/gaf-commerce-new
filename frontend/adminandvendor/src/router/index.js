import { createRouter, createWebHistory } from "vue-router";
import { createPinia } from "pinia";

import VendorLogin from "@/views/vendors/login.vue";
import VendorRegister from "@/views/vendors/register.vue";
import Vendorlayout from "@/components/layouts/vendorlayout.vue";
import VendorDashboard from "@/views/vendors/dashboard.vue";
import Addproduct from "@/views/vendors/addproduct.vue";
import Viewproduct from "@/views/vendors/viewproducts.vue";
import Profile from "@/views/vendors/profile.vue";

import AdminDashboard from "@/views/admin/dashboard.vue"; // Assuming you have this component
import AdminLayout from "@/components/layouts/adminlayout.vue"; // Assuming you have this component

import { useVendorStore } from "@/stores/vendor";

const pinia = createPinia();

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // Vendor Routes
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

    // Admin Routes
    {
      path: "/admin",
      name: "adminLayout",
      component: AdminLayout,
      children: [
        {
          path: "/admin",
          name: "adminDashboard",
          component: AdminDashboard, 
        },
        // Add more admin routes here if needed
      ],
    },
  ],
});

router.beforeEach(async (to, from, next) => {
  const { vendor, checkAuthStatus } = useVendorStore();

  // Only apply authentication checks for vendor routes
  if (
    to.path.startsWith("/vendor") &&
    to.path !== "/vendor/register" &&
    to.path !== "/vendor/login" &&
    !vendor.isAuthenticated
  ) {
    return next({ name: "vendorLogin" });
  }

  // Admin routes are not protected for now
  next();
});

export default router;