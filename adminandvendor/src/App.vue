<script setup>
import { onBeforeMount, onMounted, computed } from 'vue';
import { RouterView, useRouter } from 'vue-router';

import { useVendorStore } from './stores/vendor';

import axiosPrivate from "./api/axiosPrivate"



const router = useRouter();
const { vendor } = useVendorStore();

onMounted( async () => {
  try{
     await axiosPrivate.get("/verify/token");
  }catch(err){
    console.log(err)
    if(err?.response?.status === 401){
        router.push({name: "vendorLogin"})
    }
  }
})

</script>

<template>
  <RouterView/>
</template>
