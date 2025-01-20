<script setup>
import Header from "./components/Header.vue";
import Footer from "./components/Footer.vue";
import { onMounted, onUnmounted } from "vue";
import { productStore } from "./stores/product";
import { userStore } from "./stores/user";

const { getAllProduct, getLatestProducts } = productStore();
// const { getWishlist } = userStore();



let interval;

onMounted(() => {

    getAllProduct();
    getLatestProducts();

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
