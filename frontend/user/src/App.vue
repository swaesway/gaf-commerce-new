<script setup>
import Header from "./components/Header.vue";
import Footer from "./components/Footer.vue";
import { onBeforeMount } from "vue";
import { productStore } from "./stores/product";

const { getAllProduct, getLatestProducts } = productStore();

onBeforeMount(() => {
  getAllProduct();
  getLatestProducts();
});








</script>

<template>
  <main>
    <RouterView />
  </main>
</template>

<style scoped></style>
