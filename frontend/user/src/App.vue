<script setup>
import Header from "./components/Header.vue";
import Footer from "./components/Footer.vue";
import { onBeforeMount } from "vue";
import { productStore } from "./stores/product";
import { userStore } from "./stores/user";

const { getAllProduct, getLatestProducts } = productStore();
const { getWishlist } = userStore();

onBeforeMount(() => {
  setInterval(() => {
    getAllProduct();
    getLatestProducts();
   
}, 5000)
  
});










</script>

<template>
  <main>
    <RouterView />
  </main>
</template>

<style scoped></style>
