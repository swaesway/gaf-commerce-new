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
          <div class="card card-equal-height">
            <div
              class="card-body profile-card pt-4 d-flex flex-column align-items-center"
            >
              <img
                src="https://www.gravatar.com/avatar/2c7d99fe281ecd3bcd65ab915bac6dd5?s=250"
                alt="Profile"
                class="imgprofile"
              />
              <h2>{{ vendor.details.shopname }}</h2>
            </div>
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
                <div class="card-body">
                  <h5 class="card-title">Callbacks <span>| Manage</span></h5>
                  <div class="d-flex align-items-center">
                    <div
                      class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                    >
                      <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="ps-3">
                      <h6>33</h6>
                      <span class="text-danger small pt-1 fw-bold">12%</span>
                      <span class="text-muted small pt-2 ps-1">decrease</span>
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
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Products <span>| Manage</span></h5>
                  <div class="d-flex align-items-center">
                    <div
                      class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                    >
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6>22</h6>
                      <span class="text-success small pt-1 fw-bold">12%</span>
                      <span class="text-muted small pt-2 ps-1">increase</span>
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
                  <table class="table table-borderless">
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
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref, onMounted, onBeforeMount } from "vue";
import * as echarts from "echarts";

import { useVendorStore } from "@/stores/vendor";
import axiosPrivate from "@/api/axiosPrivate";

const { vendor, getVendorDetails } = useVendorStore();

onBeforeMount(async () => {
  await axiosPrivate.get("/verify/token");
  getVendorDetails();
});

// References
const trafficChart = ref(null);

// Top selling products data
const topSellingProducts = ref([
  {
    id: 1,
    name: "Shea Butter Moisturizing Cream",
    image: "https://placehold.co/400",
    price: "₵64",
    sold: 124,
    revenue: "₵7,936"
  },
  {
    id: 2,
    name: "Organic Coconut Hair Oil",
    image: "https://placehold.co/400",
    price: "₵46",
    sold: 98,
    revenue: "₵4,508"
  },
  {
    id: 3,
    name: "African Black Soap Cleanser",
    image: "https://placehold.co/400",
    price: "₵59",
    sold: 74,
    revenue: "₵4,366"
  },
  {
    id: 4,
    name: "Aloe Vera Face Serum",
    image: "https://placehold.co/400",
    price: "₵32",
    sold: 63,
    revenue: "₵2,016"
  },
  {
    id: 5,
    name: "Natural Rosehip Body Oil",
    image: "https://placehold.co/400",
    price: "₵79",
    sold: 41,
    revenue: "₵3,239"
  }
])


// Initialize ECharts when mounted
onMounted(() => {
  if (trafficChart.value) {
    const chart = echarts.init(trafficChart.value);
    chart.setOption({
      tooltip: {
        trigger: "item",
        formatter: "{a} <br/>{b}: {c} ({d}%)",
      },
      legend: {
        top: "5%",
        left: "center",
      },
      series: [
        {
          name: "Product Statistics",
          type: "pie",
          radius: ["40%", "70%"],
          avoidLabelOverlap: false,
          label: {
            show: false,
            position: "center",
          },
          emphasis: {
            label: {
              show: true,
              fontSize: "18",
              fontWeight: "bold",
            },
          },
          labelLine: {
            show: false,
          },
          data: [
            {
              value: 33,
              name: "Callback Products",
              itemStyle: { color: "#fd7e14" },
            },
            {
              value: 50,
              name: "Bookmarked Items",
              itemStyle: { color: "#198754" },
            },
            {
              value: 17,
              name: "No Interactions",
              itemStyle: { color: "#0dcaf0" },
            },
          ],
        },
      ],
    });
  }
});
</script>

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
</style>
