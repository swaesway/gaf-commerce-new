<script setup>
import { ref, computed, onMounted } from "vue";
import { RouterLink, useRouter } from "vue-router";

import FadeLoader from "vue-spinner/src/FadeLoader.vue"

import {productStore} from "@/stores/products";
import { useVendorStore } from "@/stores/vendor";
import { callbackStore } from "@/stores/callbacks";


// Separate queries for name and category
const nameQuery = ref("");
// const categoryQuery = ref("");

const router = useRouter()
const { callbacks, getVendorCallbacks, callbackStatus, hideCallback } = callbackStore();


function callbackStatusFn(callbackId, status){
  callbackStatus(callbackId, status, router);
  callbacks.myCallbacks = callbacks.myCallbacks?.map((callback) => {
    if(callback.callback_id == callbackId){
       callback.callback_status = status
    }

    return callback
  })
}

function hideCallbackFn(callbackId){
  hideCallback(callbackId, router);

}


onMounted(() => {
  getVendorCallbacks();
})



// Compute unique categories for the dropdown
// const uniqueCategories = computed(() => {
//   const categories = new Set(callbacks.myCallbacks.map((product) => product.category));
//   return Array.from(categories).sort();
// });

const filteredProducts = computed(() => {
  let filtered = callbacks.myCallbacks;

  const name = nameQuery.value.toLowerCase().trim();
  if (name) {
    filtered = filtered.filter((callback) =>
      callback.service_name.toLowerCase().includes(name)
    );
  }

  // Filter by category if a category is selected
  // if (categoryQuery.value) {
  //   filtered = filtered.filter(
  //     (product) => product.category === categoryQuery.value
  //   );
  // }

  return filtered;
});


</script>


<template>
  <div class="container-fluid py-4">
    <!-- Header Section with enhanced search -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Callbacks</h2>
      <div class="d-flex gap-3">
        <div class="d-flex gap-2">
          <input
            type="search"
            class="form-control"
            placeholder="Search Client"
            v-model="nameQuery"
          />
          <!-- <select
            class="form-select"
            v-model="categoryQuery"
            style="min-width: 150px"
          >
            <option value="">All Categories</option>
            <option
              v-for="category in uniqueCategories"
              :key="category"
              :value="category"
            >
              {{ category }}
            </option>
          </select> -->
        </div>
      </div>
    </div>

    <!-- Rest of your table code stays the same until tbody -->
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Client</th>
                <th>PhoneNumber</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="callback in filteredProducts" :key="callback.callback_id" @click="router.push('/vendor/callback/' + callback.callback_id)">
                <!-- Rest of your existing table row code -->
                <td>#{{ callback.callback_id }}</td>
                <td>#{{ callback.id }}</td> 
                <td>{{ callback.service_name }}</td>
                <td>{{ callback.service_telephone}}</td>
                <td>
                  <span
                   v-if="callback.callback_status === '2010'"
                    class="bage bg-warning px-2 py-1" style="border-radius: 5px;"
                  >
                   pending
                  </span>
                  <span
                   v-if="callback.callback_status === '0401'"
                    class="bage bg-danger px-2 py-1" style="border-radius: 5px;"
                  >
                    declined
                  </span>
                  <span
                  v-if="callback.callback_status === '1010'"
                    class="bage bg-success px-2 py-1" style="border-radius: 5px;"
                  >
                    approved
                  </span>
                </td>
                <td>
                  <div class="dropdown">
                    <button
                      class="btn btn-secondary btn-sm dropdown-toggle"
                      type="button"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      Actions
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <RouterLink
                          class="dropdown-item"
                          to="#"
                          @click.prevent="callbackStatusFn(callback?.callback_id, '0401')"
                        >
                        <i class="me-2"></i>❎decline
                        </RouterLink>
                      </li>
                      <li>
                        <button
                          class="dropdown-item"
                          to="#"
                          @click.prevent="callbackStatusFn(callback?.callback_id, '1010')"
                         
                        >
                          <i
                            class="me-2"
                          ></i>
                           ✅ approve
                        </button>
                      </li>
                      <li><hr class="dropdown-divider" /></li>
                      <li>
                        <button
                          class="dropdown-item"
                          
                          @click.prevent="hideCallbackFn(callback.callback_id)"
                          
                        >
                          <i class="me-2"></i>❌Delete
                      </button>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="callbacks.isCallbackLoading" class="my-2" style="display: flex; justify-content: center;">
          <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
          <!-- <span>loading ... </span> -->
        </div>

        <div v-if="callbacks.myCallbacks.length === 0 && !callbacks.isCallbackLoading" class="text-center py-4">
          <p class="text-muted">
            You have no callbacks
          </p>
        </div>
      </div>
    </div>
  </div>
</template>



<style scoped>
.dropdown-item i {
  width: 1rem;
  margin-right: 0.5rem;
}
</style>
