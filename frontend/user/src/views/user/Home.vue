<script setup>
import CategoryCard from "@/components/CategoryCard.vue";
import ProductCard from "@/components/ProductCard.vue";
import { productStore } from "@/stores/product";
import { userStore } from "@/stores/user";
import { onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";

import FadeLoader from "vue-spinner/src/FadeLoader.vue";

// export default {
//   components: {
//     CategoryCard,
//     ProductCard,
//   },
// };

const router = useRouter();
// const { product } = productStore();
const { store, getWishlist, currentUser, getAllProduct, getLatestProducts  } = userStore();

let interval;

onMounted(() => {
    
  currentUser(router);
  getWishlist(router);

    getAllProduct();
    getLatestProducts();
    // getWishlist(router);
    

    interval = setInterval(() => {
    getAllProduct();
    getLatestProducts();
  }, 5000);

  
});


onUnmounted(() => {
  clearInterval(interval);
})

</script>

<template>
  <div>
    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
      <div class="row px-xl-5">
        <div class="col-lg-8">
          <div
            id="header-carousel"
            class="carousel slide carousel-fade mb-30 mb-lg-0"
            data-ride="carousel"
          >
            <ol class="carousel-indicators">
              <li
                data-target="#header-carousel"
                data-slide-to="0"
                class="active"
              ></li>
              <li data-target="#header-carousel" data-slide-to="1"></li>
              <li data-target="#header-carousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div
                class="carousel-item position-relative active"
                style="height: 430px"
              >
                <img
                  class="position-absolute w-100 h-100"
                  src="/assets/img/Army.png"
                  style="object-fit: cover"
                />
                <div
                  class="carousel-caption d-flex flex-column align-items-center justify-content-center"
                >
                  <div class="p-3" style="max-width: 700px">
                    <h1
                      class="display-4 text-white mb-3 animate__animated animate__fadeInDown"
                    >
                      Army
                    </h1>
                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                      Gear Up with Pride. Celebrate the strength and dedication
                      of the Army with exclusive merchandise crafted for heroes
                      and their supporters. From rugged outdoor essentials to
                      stylish apparel, find everything you need to honor the
                      commitment of our land defenders. Shop now and showcase
                      your Army pride!
                    </p>
                    <a
                      class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                      href="#"
                      >Shop Now</a
                    >
                  </div>
                </div>
              </div>
              <div
                class="carousel-item position-relative"
                style="height: 430px"
              >
                <img
                  class="position-absolute w-100 h-100"
                  src="/assets/img/Navy.png"
                  style="object-fit: cover"
                />
                <div
                  class="carousel-caption d-flex flex-column align-items-center justify-content-center"
                >
                  <div class="p-3" style="max-width: 700px">
                    <h1
                      class="display-4 text-white mb-3 animate__animated animate__fadeInDown"
                    >
                      Navy
                    </h1>
                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                      Navigate Style and Function. Inspired by the courage and
                      precision of the Navy, our collection brings maritime
                      strength to your everyday life. From sleek accessories to
                      practical gear, dive into products designed for sailors
                      and sea enthusiasts alike. Discover your Navy-inspired
                      essentials today!
                    </p>
                    <a
                      class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                      href="#"
                      >Shop Now</a
                    >
                  </div>
                </div>
              </div>
              <div
                class="carousel-item position-relative"
                style="height: 430px"
              >
                <img
                  class="position-absolute w-100 h-100"
                  src="/assets/img/Air Force.png"
                  style="object-fit: cover"
                />
                <div
                  class="carousel-caption d-flex flex-column align-items-center justify-content-center"
                >
                  <div class="p-3" style="max-width: 700px">
                    <h1
                      class="display-4 text-white mb-3 animate__animated animate__fadeInDown"
                    >
                      Air Force
                    </h1>
                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                      Elevate Your Style. Soar to new heights with our Air
                      Force-inspired collection. Whether you’re looking for bold
                      apparel, tech gadgets, or unique keepsakes, our store has
                      what you need to celebrate the innovation and excellence
                      of our air guardians. Shop now to take flight in style!
                    </p>
                    <a
                      class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                      href="#"
                      >Shop Now</a
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="product-offer mb-30" style="height: 200px">
            <img class="img-fluid" src="/assets/img/accessories.jpg" alt="" />
            <div class="offer-text">
              <h3 class="text-white mb-3">Accessories</h3>
              <a href="#" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
          <div class="product-offer mb-30" style="height: 200px">
            <img class="img-fluid" src="/assets/img/knife.jpeg" alt="" />
            <div class="offer-text">
              <h6 class="text-white text-uppercase">Save 30%</h6>
              <h3 class="text-white mb-3">Tactical</h3>
              <a href="" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Carousel End -->

    <!-- Categories Start -->
    <div class="container-fluid pt-5">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Categories</span>
      </h2>

      <div class="row px-xl-5 pb-3">
        <CategoryCard name="Uniforms" image="../assets/img/uniform-1.jpeg" />
        <CategoryCard name="Fabrics" image="../assets/img/Fabric.jpeg" />
        <CategoryCard name="Electronics" image="../assets/img/cat-2.jpg" />
        <CategoryCard name="Accessories" image="../assets/img/sbag.jpeg" />
        <CategoryCard name="Footwear" image="../assets/img/sboot.png" />
        <CategoryCard name="Headgear" image="../assets/img/headgear.jpg" />
        <CategoryCard
          name="Books and Stationary"
          image="../assets/img/books.jpg"
        />
        <CategoryCard
          name="Food and Beverages"
          image="../assets/img/drinks.png"
        />
      </div>
    </div>
    <!-- Categories End -->

    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Featured Products</span>
      </h2>

      <div v-if="!store.isLoading" class="row px-xl-5">
        <ProductCard
          v-for="product in store.all_product"
          :key="product.id"
          :id="product.id"
          :name="product.title"
          :image="`http://127.0.0.1:8000/api/product/preview-image?image=${product.images[0].image}`"
          :price="`₵${product.price}`"
          :totalProductRating="product.average_rating"
          :reviews="product.ratings.length"
        />
      </div>
      <div v-else class="row px-xl-5 justify-content-center">
        <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
        <span>loading ... </span>
      </div>
    </div>
    <!-- Products End -->

    <!-- Offer Start -->
    <div class="container-fluid pt-5 pb-3">
      <div class="row px-xl-5">
        <div class="col-md-6">
          <div class="product-offer mb-30" style="height: 300px">
            <img
              class="img-fluid"
              src="../../assets/img/navyuniforms.jpg"
              alt=""
            />
            <div class="offer-text">
              <h6 class="text-white text-uppercase">Save 20%</h6>
              <h3 class="text-white mb-3">Special Offer</h3>
              <a href="" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="product-offer mb-30" style="height: 300px">
            <img
              class="img-fluid"
              src="../../assets/img/suniforms.jpg"
              alt=""
            />
            <div class="offer-text">
              <h6 class="text-white text-uppercase">Save 30%</h6>
              <h3 class="text-white mb-3">Discount</h3>
              <a href="" class="btn btn-primary">Shop Now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Offer End -->

    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Recent Products</span>
      </h2>
      <!-- <div class="row px-xl-5"> -->

      <div v-if="!store.isLoading" class="row px-xl-5">
        <ProductCard
          v-for="product in store.latestProduct"
          :key="product.id"
          :id="product.id"
          :name="product.title"
          :image="`http://127.0.0.1:8000/api/product/preview-image?image=${product.images[0].image}`"
          :price="`₵${product.price}`"
          :totalProductRating="product.average_rating"
          :reviews="product.ratings.length"
        />
      </div>
      <div
        v-else
        class="row px-xl-5 justify-content-center"
        style="margin: 0 auto"
      >
        <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
        <span>loading ... </span>
      </div>

      <!-- </div> -->
    </div>
    <!-- Products End -->

    <!-- Vendor Start -->
    <div class="container-fluid py-5">
      <div class="row px-xl-5">
        <div class="col">
          <div class="owl-carousel vendor-carousel">
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-1.jpg" alt="" />
            </div>
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-2.jpg" alt="" />
            </div>
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-3.jpg" alt="" />
            </div>
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-4.jpg" alt="" />
            </div>
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-5.jpg" alt="" />
            </div>
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-6.jpg" alt="" />
            </div>
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-7.jpg" alt="" />
            </div>
            <div class="bg-light p-4">
              <img src="/assets/img/vendor-8.jpg" alt="" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Vendor End -->
</template>
