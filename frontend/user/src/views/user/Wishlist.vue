<script setup>
import { userStore } from '@/stores/user';
import { onBeforeMount, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';




const router = useRouter();
const { user, getWishlist } = userStore();


onMounted(() => {
  setInterval(() => {
    getWishlist();
  }, 3600)
})
</script>
<template lang="">
  <div>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav class="breadcrumb bg-light mb-30">
            <RouterLink class="breadcrumb-item text-dark" to="/">Home</RouterLink>
            <span class="breadcrumb-item active">Wishlist</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Cart Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
          <table
            class="table table-light table-borderless table-hover text-center mb-0"
          >
            <thead class="thead-dark">
              <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Review</th>
                <th>Category</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="align-middle">
              <tr v-if="!user.wishList.length">
                <td colspan="5" class="text-center">
                  No products in your wishlist.
                </td>
              </tr>
              <tr v-else v-for="wishList in user.wishList" :key="wishList.id">
                <td class="align-middle">
                  <!-- <img
                    :src="`http://127.0.0.1:8000/api/product/preview-image?image=${wishList.images[0].image}`"
                    alt=""
                    style="width: 50px"
                  /> -->
                  {{wishList.title}}
                </td>
                <td class="align-middle">₵{{wishList.price}}</td>
                <td class="align-middle">
                  <small
                    v-for="n in 5"
                    :key="n"
                    class="fa fa-star text-primary mr-1"
                  ></small>
                </td>
                <td class="align-middle">{{wishList?.category}}</td>
                <td class="align-middle">
                  <button class="btn btn">
                    <i class="fa fa-heart text-primary"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-lg-4">
          <!-- Color Start -->
          <h5 class="section-title position-relative text-uppercase mb-3">
            <span class="bg-secondary pr-3">Filter by category</span>
          </h5>
          <div class="bg-light p-4 mb-30">
            <form>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  checked
                  id="color-all"
                />
                <label class="custom-control-label" for="price-all"
                  >All category</label
                >
                <span class="badge border font-weight-normal">1000</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-1"
                />
                <label class="custom-control-label" for="color-1"
                  >Uniforms</label
                >
                <span class="badge border font-weight-normal">150</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-2"
                />
                <label class="custom-control-label" for="color-2"
                  >Clothes</label
                >
                <span class="badge border font-weight-normal">295</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-3"
                />
                <label class="custom-control-label" for="color-3"
                  >Electronics</label
                >
                <span class="badge border font-weight-normal">246</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-1"
                />
                <label class="custom-control-label" for="color-1"
                  >Cosmetics</label
                >
                <span class="badge border font-weight-normal">150</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-2"
                />
                <label class="custom-control-label" for="color-2"
                  >Footwear</label
                >
                <span class="badge border font-weight-normal">295</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-4"
                />
                <label class="custom-control-label" for="color-4"
                  >Headgear</label
                >
                <span class="badge border font-weight-normal">145</span>
              </div>

              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-2"
                />
                <label class="custom-control-label" for="color-2"
                  >Books & Stationary</label
                >
                <span class="badge border font-weight-normal">295</span>
              </div>

              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-5"
                />
                <label class="custom-control-label" for="color-5"
                  >Food & Beverages</label
                >
                <span class="badge border font-weight-normal">168</span>
              </div>
            </form>
          </div>
          <!-- Color End -->
        </div>
      </div>
    </div>
    <!-- Cart End -->
  </div>
</template>

