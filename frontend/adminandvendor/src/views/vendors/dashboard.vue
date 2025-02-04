<script setup>

import { onMounted, onUnmounted, watchEffect } from "vue"
import { productStore } from '@/stores/products';
import { useRouter } from "vue-router";
import { callbackStore } from "@/stores/callbacks";

const router = useRouter();
const { products, getVendorProducts, averageProductsRating , todaysProductPercentage,  monthsProductPercentage,
  yearsProductPercentage } = productStore();

const { callbacks } = callbackStore();


function todaysProductPercentageFn(){
  todaysProductPercentage();
}

function monthsProductPercentageFn(){
  monthsProductPercentage();
}

function yearsProductPercentageFn(){
  yearsProductPercentage();
}

function viewProducts(){
  router.push({name: "viewproducts"});
  return
}

function viewCallbacks(){
  router.push({name: "callbacks"});
  return
}

let interval;
onMounted(() => {
  getVendorProducts(router);
  averageProductsRating(router)
  interval = setInterval(() => {
    averageProductsRating(router)
  }, 5000);

  todaysProductPercentage();
})

onUnmounted(() => {
  clearInterval(interval);
})


</script>



<template>
  <div>
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/vendor/">Dashboard</a></li>
        </ol>
      </nav>
    </div>
  </div>

  <main class="main">
    <!-- <div class="pagetitle">
      <h1>My Shop</h1>
    </div> -->

    <section class="section dashboard">
      <div class="row">
        <!-- Left Column -->
        <div class="col-lg-4">
          <!-- Profile Card -->
  <div class=" card review-summary">
  <div class="rating-score">{{ products.totalProductAverage.toFixed(2) }}</div>
  <div class="text-warning mb-2">
    <!-- Render filled stars -->
    <i v-for="star in Math.floor(products.totalProductAverage)" :key="`filled-${star}`" class="fas fa-star"></i>
    <!-- Render half star if applicable -->
    <i v-if="products.totalProductAverage % 1 !== 0" class="fas fa-star-half-alt"></i>
    <!-- Render empty stars -->
    <i v-for="star in 5 - Math.ceil(products.totalProductAverage)" :key="`empty-${star}`" class="far fa-star"></i>
            </div>
  <p class="verified-text">All from verified product</p>
</div>

          <!-- Product Statistics -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"
                ><i class="bi bi-three-dots"></i
              ></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>
                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>
            <div class="card-body pb-0">
              <h5 class="card-title">
                Product Statistics <span>| Today</span>
              </h5>
              <div
                id="trafficChart"
                ref="trafficChart"
                style="min-height: 400px"
                class="echart"
              ></div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-8">
          <div class="row">
            <!-- Callbacks Card -->
            <div class="col-md-6">
              <div class="card info-card customers-card card-equal-height">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"
                    ><i class="bi bi-three-dots"></i
                  ></a>
                  <ul
                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"
                  >
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                
                  <div class="card-body callbacks-card" @click="viewCallbacks">
                  <h5 class="card-title">Callbacks <span>| Manage</span></h5>
                  <div class="d-flex align-items-center">
                    <div
                      class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                    >
                      <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{  callbacks.myCallbacks.length }}</h6>
                      <!-- <span class="text-danger small pt-1 fw-bold">12%</span>
                      <span class="text-muted small pt-2 ps-1">decrease</span> -->
                    </div>
                  </div>
                </div>
             
              </div>
            </div>

            <!-- Products Card -->
            <div class="col-md-6">
              <div class="card info-card sales-card card-equal-height">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"
                    ><i class="bi bi-three-dots"></i
                  ></a>
                  <ul
                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"
                   
                  >
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#"  @click="todaysProductPercentageFn">Today</a></li>
                    <li><a class="dropdown-item" href="#"  @click="monthsProductPercentageFn">This Month</a></li>
                    <li><a class="dropdown-item" href="#"  @click="yearsProductPercentageFn">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body product-card" @click="viewProducts">
                  <h5 class="card-title"> Product <span>| Manage</span></h5>
                  <div class="d-flex align-items-center">
                    <div
                      class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                    >
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{  products.vendorProducts.length  }}</h6>
                      <span class="small pt-1 fw-bold" :class="products.percentageOfProduct?.percentageChangeY > 0 ? 'text-danger' : 'text-success' ">{{ products.percentageOfProduct?.percentageChangeY > 0 ?  products.percentageOfProduct?.percentageChangeY : products.percentageOfProduct?.percentageChangeT}}</span>
                      <span class="text-muted small pt-2 ps-1">{{ products.percentageOfProduct?.percentageChangY > 0 ? 'decress' : 'increase' }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"
                    ><i class="bi bi-three-dots"></i
                  ></a>
                  <ul
                    class="dropdown-menu dropdown-menu-end dropdown-menu-arrow"
                  >
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Today</span></h5>
                  <!-- <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="product in topSellingProducts"
                        :key="product.id"
                      >
                        <th scope="row">
                          <a href="#"
                            ><img :src="product.image" :alt="product.name"
                          /></a>
                        </th>
                        <td>
                          <a href="#" class="text-primary fw-bold">{{
                            product.name
                          }}</a>
                        </td>
                        <td>{{ product.price }}</td>
                        <td class="fw-bold">{{ product.sold }}</td>
                        <td>{{ product.revenue }}</td>
                      </tr>
                    </tbody>
                  </table> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>



<style scoped>
.imgprofile {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border: 2px solid #f2f2f2;
}

.card-equal-height {
  height: 150px;
}

.product-card:hover{
  cursor: pointer;
}

.callbacks-card:hover{
  cursor: pointer;
}

.review-summary {
    background: white; /* Light gray background */
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    max-width: 400px;

  }

  .rating-score {
    font-size: 32px;
    font-weight: bold;
    color: #222;
  }

  .stars {
    color: #ffc107; /* Gold color */
    font-size: 20px;
  }

  .verified-text {
    color: #009f5c;
    font-size: 14px;
    margin-top: 5px;
  }

</style>
