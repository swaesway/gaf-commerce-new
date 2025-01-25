<script setup>
import { userStore } from '@/stores/user';
import { onBeforeMount, onBeforeUpdate, onMounted, onUnmounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';

import FadeLoader  from 'vue-spinner/src/FadeLoader.vue';

const route = useRoute();
const router = useRouter();
const { store, getWishlist, deleteProductFromWishlist } = userStore();

const categories = ref([]);
const filteredWishlist = ref(store.wishList);

const formCategories = ref([
{isChecked: false, category: "All"},
{isChecked: false, category: "Uniforms"},
{isChecked: false, category: "Clothes"} ,
{isChecked: false, category: "Electronics"},
{isChecked: false, category: "Cosmetics"},
{isChecked: false, category: "Footwear"},
{isChecked: false, category: "Headgear"},
{isChecked: false, category: "Books and Stationary"},
{isChecked: false, category: "Food and Beverages"}]);

function isCategoryChecked(category){
  return  route.query.categories?.includes(category);
}

function addCategories(data){
 
if(categories.value.includes("All") && data.category !== "All"){ 
  categories.value = categories.value.filter((category) => category !== "All");

  router.push({
  name: "Wishlist",
  query: {categories:[...categories.value]},
  });
}  

if(data.category === "All"){ 
  categories.value = categories.value.filter((category) => category === "All");

  router.push({
  name: "Wishlist",
  query: {categories:[...categories.value]},
  });

}  

 if(categories.value.includes(data.category)){
 categories.value =  categories.value.filter((category) => category !== data.category)
}else{
 categories.value.push(data.category);
}

router.push({
  name: "Wishlist",
  query: {categories:[...categories.value]},
  
});

}



function deleteProductFromWishlistFn(productId){
  deleteProductFromWishlist(productId, router);
  // store.wishList = store.wishList.filter((wishlist) => wishlist.product.id !== productId);
  getWishlist(router);
}

// let interval;

onMounted(() => {

  if(route.path === "/wishlist" && !route.query.categories){
    router.replace("/wishlist?categories=All");
  }

  categories.value = route.query.categories ? Array.isArray(route.query.categories) ? route.query.categories : [route.query.categories] : []


    getWishlist(router);
    // interval = setInterval(() => {
    //   getWishlist(router);
    //   console.log("hiiii")
    // }, 5000);
});

// onUnmounted(() => {
//   clearInterval(interval)
// });

onBeforeUpdate(() => {

  if(!route?.query?.categories.length){
     filteredWishlist.value = store.wishList;
     return
  }

  if(route?.query?.categories.includes("All")){
    filteredWishlist.value = store.wishList;
    return 
  }

  filteredWishlist.value = store.wishList.filter((wishlist) => route?.query?.categories.includes(wishlist.product.category));

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
              
              <tr v-if="store.isLoading">
                <td colspan="5" class="row justify-content-center">
                  <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
                  <span>loading ... </span>
                </td>
              </tr>
              <tr v-else v-for="wishList in filteredWishlist || store.wishList" :key="wishList.id">
                <td v-if="store.wishList.length === 0">
                    <span>you have no wishlist  <RouterLink to="/shop?price_range=All&categories=All">try to add some</RouterLink></span>
                </td>
                <!-- <RouterLink :to="'/product-details/' + wishList.id"> -->
                  <td v-else >
                  <img
                    :src="`http://127.0.0.1:8000/api/product/preview-image?image=${wishList.product.images[0].image}`"
                    alt=""
                    style="width: 50px"
                  />
                  {{wishList.product.title}}
                </td>
                <!-- </RouterLink> -->
                <td class="align-middle">₵{{wishList.product.price}}</td>
                <td class="align-middle">
                  <small
                    v-for="n in 5"
                    :key="n"
                    class="fa fa-star text-primary mr-1"
                  ></small>
                </td>
                <td class="align-middle">{{wishList?.product.category}}</td>
                <td class="align-middle">
                  <button title="remove" @click="deleteProductFromWishlistFn(wishList.product.id)" class="btn btn">
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
            <form v-for="(data, index) in formCategories" :key="index">
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  :checked="isCategoryChecked(data.category, route.query?.categories)"
                  :id="data.category"
                  @click="addCategories(data)"
                />
                <label class="custom-control-label" :for="data.category"
                  >{{data.category}}</label
                 
                >
                <span class="badge border font-weight-normal">1000</span>
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

