<script setup>

import { onBeforeMount, onMounted } from 'vue';

import { useVendorStore } from '@/stores/vendor';

import { RouterView, useRouter } from 'vue-router';


import axiosPrivate from "@/api/axiosPrivate"

import { useToast } from "vue-toastification";


const router = useRouter();
const toast = useToast();
const { vendor, getVendorDetails } = useVendorStore();

onBeforeMount(async() => {
    await axiosPrivate.get("/verify/token");
    getVendorDetails();
});


</script>

<template>
    <div>
        <h5>Welcome {{ vendor.details.email  }}</h5>
        <ul>
            <li>shopname: {{ vendor.details.shopname }}</li>
        </ul>
        <button class="btn" @click="a"></button>
    </div>
</template>