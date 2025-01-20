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
  reviews: {
    type: Number,
    default: 0,
  },
});

const router = useRouter();
const { addProductToWishlist } = userStore();

function addToWishlist(id) {
  addProductToWishlist(id, router);
}
</script>

<template>
  <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
    <div class="product-item bg-light mb-4">
      <div class="product-img position-relative overflow-hidden">
        <img class="img-fluid product-image" :src="image" :alt="name" />
        <div class="product-action">
          <a class="btn btn-outline-dark btn-square" href="">
            <i class="fas fa-comment"></i>
          </a>
          <a @click="addToWishlist(id)" class="btn btn-outline-dark btn-square">
            <i class="far fa-heart"></i>
          </a>
          <RouterLink
            class="btn btn-outline-dark btn-square"
            :to="'/product-details/' + id"
          >
            <i class="far fa-eye"></i>
          </RouterLink>
        </div>
      </div>
      <div class="text-center py-4">
        <a class="h6 text-decoration-none text-truncate" href="">{{ name }}</a>
        <div class="d-flex align-items-center justify-content-center mt-2">
          <h5>{{ price }}</h5>
          <!-- Uncomment and pass oldPrice prop for a discounted price -->
          <!-- <h6 class="text-muted ml-2"><del>{{ oldPrice }}</del></h6> -->
        </div>
        <div class="d-flex align-items-center justify-content-center mb-1">
          <small
            v-for="n in 5"
            :key="n"
            class="fa fa-star text-primary mr-1"
          ></small>
          <small>({{ reviews }})</small>
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
