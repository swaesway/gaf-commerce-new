<script setup>
import { onMounted, onBeforeMount, onBeforeUnmount, watchEffect } from "vue";
import { RouterLink, RouterView, useRouter } from "vue-router";
import { computed } from "vue";
import moment from "moment"
import { useRoute } from "vue-router";
import { useVendorStore } from "@/stores/vendor";
import { callbackStore } from "@/stores/callbacks";


const router = useRouter();
const { vendor, getVendorDetails, logOutFn } = useVendorStore();
const { callbacks, getVendorCallbacks, viewCallback} = callbackStore();

let unreadCallbacks = [];

function logOutVendor(){
  logOutFn(router)
}

function viewCallbackFn(callbackId){
  viewCallback(callbackId, router);
}


watchEffect(() => {
   unreadCallbacks = callbacks.myCallbacks.filter((callback) => callback.callback_view === 0);
})

let interval;
onBeforeMount(async () => {
  // await axiosPrivate.get("/verify/token");
  getVendorDetails();
  getVendorCallbacks();
  interval = setInterval(() => {
    getVendorCallbacks();
  }, 50000); 


});


onBeforeUnmount(() => {
  clearInterval(interval);
})

onMounted(() => {
  // Initialize any template JavaScript here
  const select = (el, all = false) => {
    el = el.trim();
    if (all) {
      return [...document.querySelectorAll(el)];
    } else {
      return document.querySelector(el);
    }
  };

  // Toggle sidebar
  const toggleSidebar = () => {
    select("body").classList.toggle("toggle-sidebar");
  };

  if (select(".toggle-sidebar-btn")) {
    select(".toggle-sidebar-btn").addEventListener("click", toggleSidebar);
  }
});

const route = useRoute();

// Function to check if current route matches given path
const isCurrentRoute = (path) => {
  return route.path === path;
};

// Computed property to check if any products-related route is active
const isProductsMenuActive = computed(() => {
  return (
    route.path.includes("/vendor/addproduct") ||
    route.path.includes("/vendor/viewproducts")
  );
});
</script>











<template>
  <div>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
      <div class="d-flex align-items-center justify-content-between">
        <RouterLink to="/" class="logo d-flex align-items-center">
          <img src="/assets/img/logo.png" alt="" />
          <!-- <span class="d-none d-lg-block text-white">Vendor Board</span> -->
        </RouterLink>
        <i class="bi bi-list toggle-sidebar-btn text-white"></i>
      </div>
      <!-- End Logo -->

      <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
          <li class="nav-item dropdown">
            <RouterLink class="nav-link nav-icon" to="#" data-bs-toggle="dropdown">
              <i class="bi bi-bell text-white"></i>
              <span class="badge bg-primary badge-number">4</span> </RouterLink
            ><!-- End Notification Icon -->

            <ul
              class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications"
            >
              <li class="dropdown-header">
                You have 4 new notifications
                <RouterLink to="#"
                  ><span class="badge rounded-pill bg-primary p-2 ms-2"
                    >View all</span
                  ></RouterLink
                >
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>

              <li class="notification-item">
                <i class="bi bi-exclamation-circle text-warning"></i>
                <div>
                  <h4>Lorem Ipsum</h4>
                  <p>Quae dolorem earum veritatis oditseno</p>
                  <p>30 min. ago</p>
                </div>
              </li>

              <li>
                <hr class="dropdown-divider" />
              </li>

              <li class="notification-item">
                <i class="bi bi-x-circle text-danger"></i>
                <div>
                  <h4>Atque rerum nesciunt</h4>
                  <p>Quae dolorem earum veritatis oditseno</p>
                  <p>1 hr. ago</p>
                </div>
              </li>

              <li>
                <hr class="dropdown-divider" />
              </li>

              <li class="notification-item">
                <i class="bi bi-check-circle text-success"></i>
                <div>
                  <h4>Sit rerum fuga</h4>
                  <p>Quae dolorem earum veritatis oditseno</p>
                  <p>2 hrs. ago</p>
                </div>
              </li>

              <li>
                <hr class="dropdown-divider" />
              </li>

              <li class="notification-item">
                <i class="bi bi-info-circle text-primary"></i>
                <div>
                  <h4>Dicta reprehenderit</h4>
                  <p>Quae dolorem earum veritatis oditseno</p>
                  <p>4 hrs. ago</p>
                </div>
              </li>

              <li>
                <hr class="dropdown-divider" />
              </li>
              <li class="dropdown-footer">
                <RouterLink href="#">Show all notifications</RouterLink>
              </li>
            </ul>
            <!-- End Notification Dropdown Items -->
          </li>
          <!-- End Notification Nav -->

          <li class="nav-item dropdown">
            <RouterLink class="nav-link nav-icon" to="#" data-bs-toggle="dropdown">
              <i class="bi bi-chat-left-text text-white"></i>
              <span class="badge bg-success badge-number">{{ callbacks.myCallbacks.length }}</span> </RouterLink
            ><!-- End Messages Icon -->

            <ul
              class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages"
            >
            <li class="dropdown-header">
                You have {{ unreadCallbacks.length }} unread callback
                <!-- <RouterLink to="/vendor/callbacks"
                  ><span class="badge rounded-pill bg-primary p-2 ms-2"
                    >View all</span
                  ></RouterLink
                > -->
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              
              <div v-for="(callback, index) in callbacks.myCallbacks" :key="index">
                <RouterLink :to="'/vendor/callback/' + callback.callback_id" @click="viewCallbackFn(callback.callback_id)">
                <li class="message-item">
                <RouterLink :to="'/vendor/callback/' + callback.callback_id">
                  <img src="" alt="" class="rounded-circle" />
                  <div>
                    <h4 :style="callback?.callback_view ? 'color:#ababae' : 'color:black'">{{ callback?.service_name }}</h4>
                    <p>
                      {{ callback?.service_telephone }}
                    </p>
                    <p>{{ moment(callback?.callback_created_at).fromNow() }}</p>
                  </div>
                </RouterLink>
              </li>
            </RouterLink>
              <li>
                <hr class="dropdown-divider" />
              </li>
              
              </div>

              <li class="dropdown-footer">
                <RouterLink to="/vendor/callbacks">Show all callbacks</RouterLink>
              </li>
            </ul>
            <!-- End Messages Dropdown Items -->
          </li>
          <!-- End Messages Nav -->

          <li class="nav-item dropdown pe-3">
            <RouterLink
              class="nav-link nav-profile d-flex align-items-center pe-0"
              to="#"
              data-bs-toggle="dropdown"
            >
              <img
                src="https://www.gravatar.com/avatar/2c7d99fe281ecd3bcd65ab915bac6dd5?s=250"
                alt="Profile"
                class="rounded-circle text-white"
              />
              <span
                class="d-none d-md-block dropdown-toggle ps-2 text-white"
              ></span> </RouterLink
            ><!-- End Profile Iamge Icon -->

            <ul
              class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
            >
              <li class="dropdown-header">
                <h6>{{ vendor.details.shopname }}</h6>
                <span>{{ vendor.details.email }}</span>
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>

              <li>
                <RouterLink
                  class="dropdown-item d-flex align-items-center"
                  to="/vendor/profile"
                >
                  <i class="bi bi-person"></i>
                  <span>My Profile</span>
                </RouterLink>
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>

              <li>
                <RouterLink
                  class="dropdown-item d-flex align-items-center"
                  to="pages-faq.html"
                >
                  <i class="bi bi-question-circle"></i>
                  <span>Need Help?</span>
                </RouterLink>
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>

              <li @click="logOutVendor">
                <a class="dropdown-item d-flex align-items-center" >
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Sign Out</span>
                </a>
              </li>
            </ul>
            <!-- End Profile Dropdown Items -->
          </li>
          <!-- End Profile Nav -->
        </ul>
      </nav>
      <!-- End Icons Navigation -->
    </header>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
      <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
          <router-link
            to="/vendor"
            class="nav-link"
            :class="{ collapsed: !isCurrentRoute('/vendor') }"
          >
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            :class="{ collapsed: !isProductsMenuActive }"
            data-bs-target="#components-nav"
            data-bs-toggle="collapse"
            to="/vendor/addproduct"
          >
            <i class="bi bi-menu-button-wide"></i>
            <span>Products</span>
            <i class="bi bi-chevron-down ms-auto"></i>
          </router-link>
          <ul
            id="components-nav"
            class="nav-content collapse"
            :class="{ show: isProductsMenuActive }"
            data-bs-parent="#sidebar-nav"
          >
            <li>
              <router-link
                to="/vendor/addproduct"
                :class="{ active: isCurrentRoute('/vendor/addproduct') }"
              >
                <i class="bi bi-circle"></i>
                <span>Add Products</span>
              </router-link>
            </li>
            <li>
              <router-link
                to="/vendor/viewproducts"
                :class="{ active: isCurrentRoute('/vendor/viewproducts') }"
              >
                <i class="bi bi-circle"></i>
                <span>My Products</span>
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </aside>
    <!-- End Sidebar-->

    <main id="main" class="main">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
body a {
  text-decoration: none;
  -webkit-user-select: none; /* Safari */
  -ms-user-select: none; /* IE 10 and IE 11 */
  user-select: none;
}

input,
textarea {
  -webkit-user-select: text; /* Safari */
  -ms-user-select: text; /* IE 10 and IE 11 */
  user-select: text; /* Standard syntax */
}

.header {
  background-color: #0a2d02;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 10px 15px;
  color: #012970;
  transition: 0.3s;
}

.nav-link.collapsed {
  background: transparent;
  color: #012970;
}

.nav-link i {
  font-size: 16px;
  margin-right: 10px;
}

.nav-content a {
  display: flex;
  align-items: center;
  padding: 10px 0 10px 40px;
  color: #012970;
  text-decoration: none;
  transition: 0.3s;
}

.nav-content a.active,
.nav-content a:hover {
  color: #4154f1;
}

.nav-content a i {
  font-size: 6px;
  margin-right: 8px;
  line-height: 0;
  border-radius: 50%;
}
</style>


