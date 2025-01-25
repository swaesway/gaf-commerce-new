<script setup>
import { userStore } from "@/stores/user";
import { defineProps } from "vue";
import { useRouter } from "vue-router";

defineProps({
  id: {
    type: Number,
  },
  name: {
    type: String,
    required: true,
  },
  image: {
    type: String,
    required: true,
  },
  price: {
    type: String,
    required: true,
  },
  totalProductRating: {
    type: Number,
    default: 0,
  },
  reviews: {
    type: Number,
    default: 0,
  },

});

const router = useRouter();

const { store, addProductToWishlist, getWishlist } = userStore();

function addToWishlist(id) {
  addProductToWishlist(id, router);
  getWishlist(router);
}


function isProductPresentInWishlist(id) {
  const foundProductInWishlist = store.wishList.find((wishlist) => wishlist.product.id == id);
  if(foundProductInWishlist) 
    return true;
  else 
    return false;
}

</script>

<template>
  <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
    <div class="product-item bg-light mb-4">
      <div class="product-img position-relative overflow-hidden">
        <img class="img-fluid product-image" :src="image" :alt="name" />
        <div class="product-action">
          <RouterLink class="btn btn-outline-dark btn-square" to="">
            <i class="fas fa-comment"></i>
          </RouterLink>
          <RouterLink @click="addToWishlist(id)" to="" :class="isProductPresentInWishlist(id) ? 'btn btn-dark btn-square': 'btn btn-outline-dark btn-square'">
            <i :class="isProductPresentInWishlist(id) ? 'fas fa-heart text-primary': 'far fa-heart'"></i>
          </RouterLink>
          <RouterLink
            class="btn btn-outline-dark btn-square"
            :to="'/product-details/' + id"
          >
            <i class="far fa-eye"></i>
          </RouterLink>
        </div>
      </div>
      <div class="text-center py-4">
        <RouterLink class="h6 text-decoration-none text-truncate" :to="'/product-details/' + id">{{ name }}</RouterLink>
        <div class="d-flex align-items-center justify-content-center mt-2">
          <h5>{{ price }}</h5>
          <!-- Uncomment and pass oldPrice prop for a discounted price -->
          <!-- <h6 class="text-muted ml-2"><del>{{ oldPrice }}</del></h6> -->
        </div>
        <div class="d-flex align-items-center justify-content-center mb-1">
          <div class="text-primary mb-2">
    <!-- Render filled stars -->
    <i v-for="star in Math.floor(totalProductRating)" :key="`filled-${star}`" class="fas fa-star"></i>
    <!-- Render half star if applicable -->
    <i v-if="totalProductRating % 1 !== 0" class="fas fa-star-half-alt"></i>
    <!-- Render empty stars -->
    <i v-for="star in 5 - Math.ceil(totalProductRating)" :key="`empty-${star}`" class="far fa-star"></i>
  </div>
          <small style="font-size: 16px; margin-top: -5px; margin-left: 10px;">({{ reviews }})</small>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.product-img {
  height: 200px; /* Fixed height for the image container */
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background-color: #f9f9f9; /* Optional: Background color */
}

.product-image {
  height: 100%; /* Fill the container's height */
  width: 100%; /* Fill the container's width */
  object-fit: cover; /* Ensure the image covers the container */
}
</style>
