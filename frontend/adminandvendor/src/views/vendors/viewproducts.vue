<script setup>
import { ref, computed, onMounted } from "vue";
import { RouterLink, useRouter } from "vue-router";

import FadeLoader from "vue-spinner/src/FadeLoader.vue"

import {productStore} from "@/stores/products";


// Separate queries for name and category
const nameQuery = ref("");
const categoryQuery = ref("");

const router = useRouter()
const { products, getVendorProducts, deleteProduct } = productStore();


function deleteProductFn(productId){
  deleteProduct(productId, router);
  getVendorProducts(router);
}


onMounted(() => {
  getVendorProducts(router);
})





// Compute unique categories for the dropdown
const uniqueCategories = computed(() => {
  const categories = new Set(products.vendorProducts.map((product) => product.category));
  return Array.from(categories).sort();
});

// Updated filtered products computed property
const filteredProducts = computed(() => {
  let filtered = products.vendorProducts;

  // Filter by name if there's a name query
  const name = nameQuery.value.toLowerCase().trim();
  if (name) {
    filtered = filtered.filter((product) =>
      product.title.toLowerCase().includes(name)
    );
  }

  // Filter by category if a category is selected
  if (categoryQuery.value) {
    filtered = filtered.filter(
      (product) => product.category === categoryQuery.value
    );
  }

  return filtered;
});


</script>


<template>
  <div class="container-fluid py-4">
    <!-- Header Section with enhanced search -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">View Products</h2>
      <div class="d-flex gap-3">
        <div class="d-flex gap-2">
          <input
            type="search"
            class="form-control"
            placeholder="Search Product"
            v-model="nameQuery"
          />
          <select
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
          </select>
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
                <th>Product Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in filteredProducts" :key="product.id">
                <!-- Rest of your existing table row code -->
                <td>#{{ product.id }}</td>
                <td>{{ product.title }}</td>
                <td>{{ product.category }}</td>
                <td>
                  <!-- <span
                    :class="[
                      'badge',
                      product.status === 'active' ? 'bg-success' : 'bg-danger',
                    ]"
                  >
                    {{ product.status }}
                  </span> -->
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
                        <a
                          class="dropdown-item"
                          href="#"
                         
                        >
                          <i class="bi bi-pencil me-2"></i>Update
                        </a>
                      </li>
                      <li>
                        <!-- <a
                          class="dropdown-item"
                          href="#"
                          
                        >
                          <i
                            class="bi"
                            :class="[
                              product.status === 'active'
                                ? 'bi-snow'
                                : 'bi-snow-fill',
                            ]"
                          ></i>
                          {{
                            product.status === "active" ? "Freeze" : "Unfreeze"
                          }}
                        </a> -->
                      </li>
                      <li><hr class="dropdown-divider" /></li>
                      <li>
                        <RouterLink
                          class="dropdown-item text-danger"
                          to="#"
                          @click.prevent="deleteProductFn(product.id)"
                        >
                          <i class="bi bi-trash me-2"></i>Delete
                        </RouterLink>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="products.isLoading" class="my-2" style="display: flex; justify-content: center;">
          <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
          <span>loading ... </span>
        </div>

        <div v-if="products.vendorProducts.length === 0 && !products.isLoading" class="text-center py-4">
          <p class="text-muted">
            No products found matching your search criteria
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
