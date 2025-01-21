<script setup>
import { ref } from "vue";

// Static data for the dashboard
const totalProducts = ref(120);
const categoriesSold = ref(["Electronics", "Clothing", "Home Decor"]);
const totalRevenue = ref(25678.45);
const topCategory = ref("Electronics");
</script>

<template>
  <div class="container my-5">
    <h2 class="mb-4">Vendor Dashboard</h2>
    <div class="row">
      <!-- Total Products Card -->
      <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-header">Total Products</div>
          <div class="card-body">
            <h5 class="card-title">{{ totalProducts }}</h5>
          </div>
        </div>
      </div>

      <!-- Categories Sold Card -->
      <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
          <div class="card-header">Categories Sold</div>
          <div class="card-body">
            <h5 class="card-title">{{ categoriesSold.length }}</h5>
            <p class="card-text">
              <strong>Details:</strong> {{ categoriesSold.join(", ") }}
            </p>
          </div>
        </div>
      </div>

      <!-- Total Revenue Card -->
      <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
          <div class="card-header">Total Revenue</div>
          <div class="card-body">
            <h5 class="card-title">${{ totalRevenue.toFixed(2) }}</h5>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <!-- Top Category Card -->
      <div class="col-md-6">
        <div class="card text-white bg-info mb-3">
          <div class="card-header">Top Selling Category</div>
          <div class="card-body">
            <h5 class="card-title">{{ topCategory }}</h5>
          </div>
        </div>
      </div>

      <!-- Add More Statistics if needed -->
      <div class="col-md-6">
        <div class="card text-white bg-secondary mb-3">
          <div class="card-header">Additional Information</div>
          <div class="card-body">
            <p class="card-text">You can display other useful statistics here.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.card {
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
