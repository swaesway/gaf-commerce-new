
<script setup>
import ProductCard from "@/components/ProductCard.vue";
import FadeLoader from "vue-spinner/src/FadeLoader.vue"
import { callbackStore } from "@/stores/callbacks"
import { onBeforeMount, onMounted, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const { callbacks, getCallbackById, callbackStatus,  hideCallback, viewCallback } = callbackStore();


function declinedCallbackFn(id, status){
  callbackStatus(id, status, router)
}

function approvedCallbackFn(id, status){
  callbackStatus(id, status, router)
}

function hideCallbackFn(id){
   hideCallback(id, router)
}


watchEffect(() => {
    if (route.params.id) {
        getCallbackById(route.params.id);
        viewCallback(route.params.id, router)

    }
})

</script>

<template>
  <div v-if="callbacks.isCallbackLoading" class="loading-container">
    <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
    <span>Loading...</span>
  </div>
  <div v-else class="content-wrapper">
    
    <div class="card-container">
      <ProductCard
        v-for="(callback, index) in callbacks.singleCallback" 
        :key="index"
        :id="callback.callback_id"
        :name="callback.title"
        :image="`http://127.0.0.1:8000/api/product/preview-image?image=${callback.images[0].image}`"
        :price="`₵${callback.price}`"
        :totalProductRating="callback.average_rating"
        reviews=""
        class="product-card"
      />
      <i class="bi bi-trash3 trash-icon" title="Remove callback" 
         @click="hideCallbackFn(callbacks.singleCallback[0]?.callback_id)"></i>
    </div>
    
    <div class="client-info">
      <span class="client-status">Client ✅✅✅</span>
      <p class="client-name">{{ callbacks.singleCallback[0]?.service_name }}</p>
      <p class="client-phone">Tel: {{ callbacks.singleCallback[0]?.service_telephone }}</p>
      <div class="action-buttons">
        <a :href="'tel:' + callbacks.singleCallback[0]?.service_telephone" class="btn call-btn">📞 Call</a>
        <a :href="'https://wa.me/' + callbacks.singleCallback[0]?.service_telephone" target="_blank" class="btn whatsapp-btn">💬 WhatsApp</a>
      </div>
    </div>
    
    <div class="button-group">
      <button 
        v-if="callbacks.singleCallback[0]?.callback_status === '0401'"
        class="btn btn-danger"
        @click="declinedCallbackFn(callbacks.singleCallback[0]?.callback_id, '0401')"
        disabled>
        Callback Declined
      </button>
      
      <button 
        v-if="['1010', '2010'].includes(callbacks.singleCallback[0]?.callback_status)"
        class="btn btn-danger"
        @click="declinedCallbackFn(callbacks.singleCallback[0]?.callback_id, '0401')">
        Decline Callback
      </button>
      
      <button 
        v-if="callbacks.singleCallback[0]?.callback_status === '1010'"
        class="btn btn-success"
        @click="approvedCallbackFn(callbacks.singleCallback[0]?.callback_id, '1010')"
        disabled>
        Callback Approved
      </button>
      
      <button 
        v-if="['0401', '2010'].includes(callbacks.singleCallback[0]?.callback_status)"
        class="btn btn-success"
        @click="approvedCallbackFn(callbacks.singleCallback[0]?.callback_id, '1010')">
        Mark as Approved
      </button>
    </div>
  </div>
</template>

<style scoped>
.loading-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.content-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.card-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  width: 400px;
  gap: 20px;
  position: relative;
}

.product-card {
  width: 300px;
  height: 250px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 15px;
  background: white;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.trash-icon {
  font-size: 24px;
  color: red;
  cursor: pointer;
  position: absolute;
  top: 10%;
  right: -40%;
}

.client-info {
  text-align: center;
  margin-top: 20px;
  padding: 10px;
  border-radius: 10px;
  background: #f8f9fa;
}

.action-buttons .btn {
  margin: 5px;
  padding: 8px 15px;
  border-radius: 5px;
  font-weight: bold;
  text-decoration: none;
}

.call-btn {
  background: #007bff;
  color: white;
}

.whatsapp-btn {
  background: #25d366;
  color: white;
}

.button-group {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  border-radius: 5px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  border: none;
}

.btn-danger {
  background: red;
  color: white;
}

.btn-success {
  background: green;
  color: white;
}
</style>
