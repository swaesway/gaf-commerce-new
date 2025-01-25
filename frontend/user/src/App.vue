<script setup>
import { useRouter } from "vue-router";
import Header from "./components/Header.vue";
import Footer from "./components/Footer.vue";
import { onMounted, onUnmounted } from "vue";
import { productStore } from "./stores/product";
import { userStore } from "./stores/user";



const router = useRouter();

// const { getAllProduct, getLatestProducts } = productStore();
const { getWishlist, currentUser, getAllProduct, getLatestProducts } = userStore();



let interval;

onMounted(() => {
    
    // currentUser(router);
    getAllProduct();
    getLatestProducts();
    // getWishlist(router);
    

    interval = setInterval(() => {
    getAllProduct();
    getLatestProducts();
  }, 5000);

  
});


onUnmounted(() => {
  clearInterval(interval);
})







</script>

<template>
  <main>
    <RouterView />
  </main>
</template>

<style scoped></style>
