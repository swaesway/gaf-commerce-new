import { createRouter, createWebHistory } from 'vue-router'
import VendorLogin from '@/views/vendors/login.vue'
import VendorRegister from '@/views/vendors/register.vue'
import Vendorlayout from '@/components/layouts/vendorlayout.vue'
import VendorDashboard from '@/views/vendors/dashboard.vue'
import Addproduct from '@/views/vendors/addproduct.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
 
    {
      path: '/vendorlogin',
      name: 'vendorlogin',
      component: VendorLogin

    },
    {
      path: '/vendorRegister',
      name: 'vendorregister',
      component: VendorRegister
    },
    {
      path: '/vendor',
      name: 'vendorlayout',
      component: Vendorlayout,
      children: [
        {
          path: 'dashboard',
          name: 'dashboard',
          component: VendorDashboard
        },
        {
          path: 'addproduct',
          name: 'addproduct',
          component: Addproduct
        }
      ]
    }

  
  ],
})

export default router
