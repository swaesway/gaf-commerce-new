<script setup>
import ProductCard from "@/components/ProductCard.vue";

// import { productStore } from "@/stores/product";
import { userStore } from "@/stores/user";
import { onBeforeMount, onMounted, onUnmounted, reactive, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

import FadeLoader  from 'vue-spinner/src/FadeLoader.vue'


const { store, filterByPricesAndCategories, searchProduct } = userStore()

const route = useRoute();
const router = useRouter();

const state = reactive({
 price_range: [],
 catgeories: []
});

const formPrices = ref([
{isChecked: false, price: "All"},
{isChecked: false, price: "0-100"},
{isChecked: false, price: "100-200"} ,
{isChecked: false, price: "200-300"},
{isChecked: false, price: "300-400"},
{isChecked: false, price: "500-600"}])

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


const prices = ref([]);
const categories = ref([]);
const sortBy = ref("Latest");

function allPrices(value){
  if(prices.value.includes(value))
    return "All";
  else 
    return "no";
}

function setSortingState(value){
  sortBy.value = value
  filterByPricesAndCategories({
    price_range: prices.value,
    categories: categories.value,
    sortBy: sortBy.value,
    all_price_range: allPrices("All"),
    all_categories: allCategories("All")
  }, router);
}

function allCategories(value){
  if(categories.value.includes(value))
    return "All";
  else 
    return "no";
}

function isPriceChecked(price){
  return  route.query.price_range?.includes(price);
}

function isCategoryChecked(category){
  return  route.query.categories?.includes(category);
}

function addPriceRange(data){

  if(prices.value.includes("All") && data.price !== "All"){ 
   prices.value = prices.value.filter((price) => price !== "All");

  router.push({
  name: "Wishlist",
  query: {price_range:[...prices.value]},
  });
}  

if(data.price === "All"){ 
  prices.value = prices.value.filter((price) => price === "All");

  router.push({
  name: "Wishlist",
  query: {price_range:[...prices.value]},
  });

}  
 
  if(prices.value.includes(data.price)){
     prices.value =  prices.value.filter((price) => price !== data.price)
  }else{
    prices.value.push(data.price);
  }

  filterByPricesAndCategories({
    price_range: prices.value,
    categories: categories.value,
    sortBy: sortBy.value,
    all_price_range: allPrices("All"),
    all_categories: allCategories("All")
  }, router);

  router.push({
    name: "Shop",
    query: {price_range: [...prices.value], categories:[...categories.value]},
    
  });

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
  console.log(categories.value)
//  console.log(prices.value)
 filterByPricesAndCategories({
   price_range: prices.value,
   categories: categories.value,
   sortBy: sortBy.value,
   all_price_range: allPrices("All"),
   all_categories: allCategories("All")
 }, router);
 


 router.push({
   name: "Shop",
   query: {price_range: [...prices.value], categories:[...categories.value]},
   
 });


}


// let interval;


onBeforeMount(() => {

prices.value = route.query.price_range
        ? Array.isArray(route.query.price_range)
          ? route.query.price_range
          : [route.query.price_range]
        : [];


categories.value = route.query.categories
        ? Array.isArray(route.query.categories)
          ? route.query.categories
          : [route.query.categories]
        : [];

        if(route.query.q){
          searchProduct({search: route.query.q})
        }
        
        filterByPricesAndCategories({
         price_range: prices.value,
         categories: categories.value,
         sortBy: sortBy.value,
         all_price_range: route.query?.price_range?.includes("All") ? "All" : "no",
         all_categories: route.query?.categories?.includes("All") ? "All" : "no"
       }, router);

      //  interval = setInterval(() => {
      //   filterByPricesAndCategories({
      //    price_range: prices.value,
      //    categories: categories.value
      //  });
      //  }, 5000);

    });

  // onUnmounted(() => {
  //   clearInterval(interval);
  // })




</script>
<template>
  <div>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav class="breadcrumb bg-light mb-30">
            <RouterLink class="breadcrumb-item text-dark" to="/">Home</RouterLink>
            <span class="breadcrumb-item active">Products</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <!-- Shop Sidebar Start -->
        <div class="col-lg-3 col-md-4">
          <!-- Price Start -->
          <h5 class="section-title position-relative text-uppercase mb-3">
            <span class="bg-secondary pr-3">Filter by price</span>
          </h5>
          <div class="bg-light p-4 mb-30">
            <form v-for="(data, index) in formPrices" :key="index">
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  :checked="isPriceChecked(data.price, route.query?.price_range)"
                  :id="index"
                  @click="addPriceRange(data)"
                />
                <label class="custom-control-label" :for="index"
                  >₵{{data.price}}</label
                 
                >
                <span class="badge border font-weight-normal">1000</span>
              </div>
              
            </form>
          </div>
          <!-- Price End -->

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
                  >{{data.category}}</label>
                <span class="badge border font-weight-normal">1000</span>
              </div>
              
            </form>
          </div>

          <!-- Size Start -->
          <!-- <h5 class="section-title position-relative text-uppercase mb-3">
            <span class="bg-secondary pr-3">Filter by size</span>
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
                  id="size-all"
                />
                <label class="custom-control-label" for="size-all"
                  >All Size</label
                >
                <span class="badge border font-weight-normal">1000</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="size-1"
                />
                <label class="custom-control-label" for="size-1">XS</label>
                <span class="badge border font-weight-normal">150</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="size-2"
                />
                <label class="custom-control-label" for="size-2">S</label>
                <span class="badge border font-weight-normal">295</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="size-3"
                />
                <label class="custom-control-label" for="size-3">M</label>
                <span class="badge border font-weight-normal">246</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="size-4"
                />
                <label class="custom-control-label" for="size-4">L</label>
                <span class="badge border font-weight-normal">145</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="size-5"
                />
                <label class="custom-control-label" for="size-5">XL</label>
                <span class="badge border font-weight-normal">168</span>
              </div>
            </form>
          </div> -->
          <!-- Size End -->
        </div>
        <!-- Shop Sidebar End -->

        <!-- Shop Product Start -->
        <div class="col-lg-9 col-md-8">
          <div class=""> <!-- edited, causing content shrinking   -->
            <div class="col-12 pb-1">
              <div
                class="d-flex align-items-center justify-content-between mb-4"
              >
                <div>
                  <button class="btn btn-sm btn-light">
                    <i class="fa fa-th-large"></i>
                  </button>
                  <button class="btn btn-sm btn-light ml-2">
                    <i class="fa fa-bars"></i>
                  </button>
                </div>
                <div class="ml-2">
                  <div class="btn-group">
                    <button
                      type="button"
                      class="btn btn-sm btn-light dropdown-toggle"
                      data-toggle="dropdown"
                    >
                      {{sortBy}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item" @click="setSortingState('Latest')">Latest</a>
                      <a class="dropdown-item" @click="setSortingState('Popularity')">Popularity</a>
                      <a class="dropdown-item" @click="setSortingState('Best Rating')">Best Rating</a>
                    </div>
                  </div>
                  <div class="btn-group ml-2">
                    <button
                      type="button"
                      class="btn btn-sm btn-light dropdown-toggle"
                      data-toggle="dropdown"
                    >
                      Showing
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item" href="#">10</a>
                      <a class="dropdown-item" href="#">20</a>
                      <a class="dropdown-item" href="#">30</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

        <div v-if="!store.isLoading" class="row px-xl-5">
        <ProductCard
          v-for="(product, index) in store.filteredProduct" :key="index"
          :id="product.id"
          :name="product.title"
          :image="`http://127.0.0.1:8000/api/product/preview-image?image=${product.images[0].image}`"
          :price="`₵${product.price}`"
          :totalProductRating="product.average_rating"
          :reviews="product.ratings?.length"
        />
      </div>
      <div v-else class="row justify-content-center" style="margin:0 auto;">
        <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
        <span>loading ... </span>

      </div>
            

            <!-- <div v-if="!product.isLoading" class="col-12">
              <nav>
                <ul class="pagination justify-content-center">
                  <li class="page-item disabled">
                    <a class="page-link" href="#">Previous</a>
                  </li>
                  <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav>
            </div> -->
          </div>
        </div>
        <!-- Shop Product End -->
      </div>
    </div>
    <!-- Shop End -->
  </div>
</template>
