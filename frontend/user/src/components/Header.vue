<script setup>
import { productStore } from "@/stores/product";
import { userStore } from "@/stores/user";
import { onMounted, reactive } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

// const { product, searchProduct } = productStore();
const { store, getWishlist , logOut, searchProduct } = userStore();

const form = reactive({
  search: "",
});

function logOutFn() {
  logOut(router);
  console.log(store.isAuthenticated, "ooo")
}

function searchBtnTriggered() {
  router.replace("/Shop?search=true&q=null");
}

function searchFn() {
  searchProduct({ search: form.search });

  router.push({
    name: "Shop",
    query: { search: true, q: form.search },
  });
}

onMounted(() => {
   getWishlist(router);
})
</script>

<template>
  <div>
    <div class="container-fluid">
      <div
        class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex"
      >
        <div class="col-lg-4">
          <RouterLink to="/" class="text-decoration-none poppins-bold text-green">
            <img src="/assets/img/logo.png" alt="logo" class="logo" />
          </RouterLink>
        </div>
        <div class="col-lg-4 col-6 text-left">
          <div class="input-group" style="width: 150%">
            <input
              v-model="form.search"
              type="text"
              class="form-control rounded-pill"
              placeholder="Search for products"
              style="
                height: 50px;
                border-radius: 50px;
                padding-right: 40px;
                width: 100%;
              "
              @click="searchBtnTriggered"
              @keyup="searchFn"
            />
            <div class="input-group-append">
              <span
                class="input-group-text bg-transparent text-primary border-0"
              >
                <i class="fa fa-search"></i>
              </span>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-6 text-right">
          <div class="btn-group">
            <button
              type="button"
              class="btn btn-sm btn-light dropdown-toggle"
              data-toggle="dropdown"
            >
              My Account
            </button>
            <div
              v-if="store.isAuthenticated"
              class="dropdown-menu dropdown-menu-right"
            >
              <a
                ><button @click="logOutFn" class="dropdown-item" type="button">
                  Sign out
                </button></a
              >
            </div>
            <div v-else class="dropdown-menu dropdown-menu-right">
              <RouterLink to="/login"
                ><button class="dropdown-item" type="button">
                  Sign in
                </button></RouterLink
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid bg-green mb-30">
      <div class="row px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
          <a
            class="btn d-flex align-items-center justify-content-between bg-primary w-100"
            data-toggle="collapse"
            href="#navbar-vertical"
            style="height: 65px; padding: 0 30px"
          >
            <h6 class="text-dark m-0">
              <i class="fa fa-bars mr-2"></i>Categories
            </h6>
            <i class="fa fa-angle-down text-dark"></i>
          </a>
          <nav
            class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
            id="navbar-vertical"
            style="width: calc(100% - 30px); z-index: 999"
          >
            <div class="navbar-nav w-100">
              <div v-if="route.path === '/shop'"></div>
              <div v-else>
                <div class="nav-item dropdown dropright">
                  <RouterLink
                    to="/shop"
                    class="nav-link dropdown-toggle"
                    data-toggle="dropdown"
                    >Uniforms <i class="fa fa-angle-right float-right mt-1"></i
                  ></RouterLink>
                  <div
                    class="dropdown-menu position-absolute rounded-0 border-0 m-0"
                  >
                    <RouterLink to="/shop" class="dropdown-item">Army</RouterLink>
                    <RouterLink to="/shop" class="dropdown-item">Navy</RouterLink>
                    <RouterLink to="/shop" class="dropdown-item">Air Forces</RouterLink>
                  </div>
                </div>
                <RouterLink
                  to="/shop?categories=Clothes"
                  class="nav-item nav-link"
                  >Clothes</RouterLink
                >
                <RouterLink
                  to="/shop?categories=Electronics"
                  class="nav-item nav-link"
                  >Electronics</RouterLink
                >
                <RouterLink
                  to="/shop?categories=Cosmetics"
                  class="nav-item nav-link"
                  >Cosmetics</RouterLink
                >
                <RouterLink
                  to="/shop?categories=Footwear"
                  class="nav-item nav-link"
                  >Footwear</RouterLink
                >
                <RouterLink
                  to="/shop?categories=Headgear"
                  class="nav-item nav-link"
                  >Headgear</RouterLink
                >
                <RouterLink
                  to="/shop?categories=Books and Stationary"
                  class="nav-item nav-link"
                  >Books & Stationary</RouterLink
                >
                <RouterLink
                  to="/shop?categories=Food and Beverages"
                  class="nav-item nav-link"
                  >Food & Beverages</RouterLink
                >
              </div>
            </div>
          </nav>
        </div>
        <div class="col-lg-9">
          <nav
            class="navbar navbar-expand-lg bg-green navbar-dark py-3 py-lg-0 px-0"
          >
            <div class="d-flex">
              <RouterLink to="/" class="d-block d-lg-none">
                <img
                  src="/assets/img/m-logo.png"
                  alt="gaf logo"
                  class="logo d-inline"
                />
              </RouterLink>
              <button
                type="button"
                class="navbar-toggler"
                data-toggle="collapse"
                data-target="#navbarCollapse"
              >
                <span class="navbar-toggler-icon"></span>
              </button>
            </div>
            <div
              class="collapse navbar-collapse justify-content-between"
              id="navbarCollapse"
            >
              <div class="navbar-nav mr-auto py-0">
                <!-- Active class will dynamically highlight the active link -->
                <RouterLink
                  to="/"
                  class="nav-item nav-link"
                  active-class="active"
                  exact-active-class="active"
                >
                  Home
                </RouterLink>
                <RouterLink
                  to="/shop?price_range=All&categories=All"
                  class="nav-item nav-link"
                  active-class="active"
                >
                  Products
                </RouterLink>
                <RouterLink
                  to="/wishlist?categories=All"
                  class="nav-item nav-link"
                  active-class="active"
                >
                  Wishlist
                </RouterLink>
                <RouterLink
                  to="/contact"
                  class="nav-item nav-link"
                  active-class="active"
                >
                  Contact
                </RouterLink>
              </div>
              <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                <RouterLink to="/wishlist" class="btn px-0">
                  <i class="fas fa-heart text-primary"></i>
                  <span
                    class="badge text-secondary border border-secondary rounded-circle"
                    style="padding-bottom: 2px"
                    >{{ store.wishList.length }}</span
                  >
                </RouterLink>
                <RouterLink to="/chat" class="btn px-0 ml-3">
                  <i class="fas fa-comment text-primary"></i>
                  <span
                    class="badge text-secondary border border-secondary rounded-circle"
                    style="padding-bottom: 2px"
                    >0</span
                  >
                </RouterLink>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.bg-green {
  background-color: var(--primary) !important;
}

.logo {
  width: 75% !important;
  height: auto !important;
}

.text-green {
  color: var(--primary) !important;
}

.input-group {
  max-width: 700px;
  margin: auto;
}

.input-group-text {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  border: none;
  background: none;
  cursor: pointer;
}
</style>
