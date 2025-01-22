<script setup>
import { useRoute, useRouter } from 'vue-router';

import FadeLoader  from 'vue-spinner/src/FadeLoader.vue';
import moment from 'moment';

import { productStore } from '@/stores/product';
import { onBeforeMount, onMounted, reactive, ref } from 'vue';
import { userStore } from '@/stores/user';

const route = useRoute();
const router = useRouter();
const { product, getProductById, getProductReviews } = productStore();
const { addProductToWishlist, getWishlist, rateProduct, requestCallback, reportProduct } = userStore();

const totalStars = ref(5);
const currentRating = ref(0)
const hoverRating = ref(0);
const showRatingText = ref(false)

const callbackText = ref("Request Callback");
const reportText = ref("Report Content");

const form = reactive({
  rating: hoverRating.value,
  comment: ""
})

  function hoverStar(star) {
      hoverRating.value = star;
  }

  function resetHover() {
      hoverRating.value = 0;
    }

    function rates(star) {
      currentRating.value = star;
      showRatingText.value = true;
    }

function addToWishlist(){
  addProductToWishlist(route.params?.id, router);
  getWishlist();
}

function rateProductFn(){
   rateProduct(route.params?.id, {rating: currentRating.value, comment: form.comment }, router);
   getProductReviews(route.params?.id);
   getProductReviews(route.params?.id);
   form.comment = "";
}

function requestCallbackFn(){
  requestCallback(route.params?.id);
  callbackText.value = "Callback Request"
}

function reportProductFn(){
  reportProduct(route.params?.id);
  reportText.value = "Content Reported"
}

onMounted(() => {
  getProductReviews(route.params?.id);
})

onMounted(() => {
  getWishlist();
  getProductById(route.params.id);
  
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
            <RouterLink class="breadcrumb-item text-dark" to="/shop?price_range=All&categories=All">Shop</RouterLink>
            <span class="breadcrumb-item active">Shop Detail</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

   <div v-if="product.isLoading" class="row justify-content-center">
    <FadeLoader :loading="true" :color="'rgb(204 208 207)'" />
    <span>loading ... </span>
   </div>
       <!-- Shop Detail Start -->
  <div v-else class="container-fluid pb-5">
      <div class="row px-xl-5">
        <div class="col-lg-5 mb-30">
          <div
            id="product-carousel"
            class="carousel slide"
            data-ride="carousel"
          >
            <div class="carousel-inner bg-light">
              <div v-for="(productImage, index) in product.singleProduct.images" :key="index" class="carousel-item" :class='{active: index === 0}'>
                <img
                  class="w-100 h-100"
                  :src="`http://127.0.0.1:8000/api/product/preview-image?image=${productImage.image}`"
                  alt="Image"
                />
              </div>
            </div>
            <a
              class="carousel-control-prev"
              href="#product-carousel"
              data-slide="prev"
            >
              <i class="fa fa-2x fa-angle-left text-dark"></i>
            </a>
            <a
              class="carousel-control-next"
              href="#product-carousel"
              data-slide="next"
            >
              <i class="fa fa-2x fa-angle-right text-dark"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-7 h-auto mb-30">
          <div class="h-100 bg-light p-30">
            <h3>{{ product.singleProduct.title }}</h3>
            <div class="d-flex mb-3">
              <div class="text-primary mr-2">
                <small class="fas fa-star"></small>
                <small class="fas fa-star"></small>
                <small class="fas fa-star"></small>
                <small class="fas fa-star-half-alt"></small>
                <small class="far fa-star"></small>
              </div>
              <small class="pt-1">(99 Reviews)</small>
            </div>
            <h3 class="font-weight-semi-bold mb-4">₵{{ product.singleProduct.price }}</h3>
            <p class="mb-4">
             {{ product.singleProduct.description}}
            </p>
            <div class="d-flex mb-3"></div>
            <div class="d-flex mb-4"></div>
            <div class="d-flex align-items-center mb-4 pt-2">
              <button @click="requestCallbackFn" class="btn bg-green px-3 mr-3 text-white">
                <i class="fa fa-arrows-rotate mr-1"></i> {{ callbackText}}
              </button>
              <button @click="addToWishlist" class="btn bg-yellow px-3 mr-3 text-white">
                <i class="fa fa-heart mr-1"></i> Add To Wishlist
              </button>

              <button @click="reportProductFn" class="btn bg-red px-3 mr-3 text-white">
                <i class="fa-solid fa-flag mr-1"></i>{{reportText}}
              </button>
            </div>
            <div class="d-flex pt-2">
              <strong class="text-dark mr-2"
                ><i class="fa-sharp fa-solid fa-shop mr-4"></i>{{product.singleProduct?.shopvendor?.shopname}}</strong
              >

              <small class="pt-1"> uploaded on {{moment(product.singleProduct.created_at).format("LLL")}}</small>
            </div>

            <div class="d-flex align-items-center mb-4 pt-2">
              <button class="btn btn-primary px-3 text-white">
                <i class="fa fa-comment mr-1"></i> Chat with Vendor
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="row px-xl-5">
        <div class="col">
          <div class="bg-light p-30">
            <div class="nav nav-tabs mb-4">
              <a
                class="nav-item nav-link text-dark active"
                data-toggle="tab"
                href="#tab-pane-1"
                >Description</a
              >
              <a
                class="nav-item nav-link text-dark"
                data-toggle="tab"
                href="#tab-pane-2"
                >Information</a
              >
              <a
                class="nav-item nav-link text-dark"
                data-toggle="tab"
                href="#tab-pane-3"
                >Reviews ({{product.productRatings.length}})</a
              >
            </div>
            <div class="tab-content">
              <div class="tab-pane fade show active" id="tab-pane-1">
                <h4 class="mb-3">Product Description</h4>
                <p>
                  {{ product.singleProduct.description}}
                </p>
              </div>
              <div class="tab-pane fade" id="tab-pane-2">
                <h4 class="mb-3">Additional Information</h4>
                <p>
                  Eos no lorem eirmod diam diam, eos elitr et gubergren diam
                  sea. Consetetur vero aliquyam invidunt duo dolores et duo sit.
                  Vero diam ea vero et dolore rebum, dolor rebum eirmod
                  consetetur invidunt sed sed et, lorem duo et eos elitr,
                  sadipscing kasd ipsum rebum diam. Dolore diam stet rebum sed
                  tempor kasd eirmod. Takimata kasd ipsum accusam sadipscing,
                  eos dolores sit no ut diam consetetur duo justo est, sit
                  sanctus diam tempor aliquyam eirmod nonumy rebum dolor
                  accusam, ipsum kasd eos consetetur at sit rebum, diam kasd
                  invidunt tempor lorem, ipsum lorem elitr sanctus eirmod
                  takimata dolor ea invidunt.
                </p>
                <div class="row">
                  <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        Sit erat duo lorem duo ea consetetur, et eirmod
                        takimata.
                      </li>
                      <li class="list-group-item px-0">
                        Amet kasd gubergren sit sanctus et lorem eos sadipscing
                        at.
                      </li>
                      <li class="list-group-item px-0">
                        Duo amet accusam eirmod nonumy stet et et stet eirmod.
                      </li>
                      <li class="list-group-item px-0">
                        Takimata ea clita labore amet ipsum erat justo voluptua.
                        Nonumy.
                      </li>
                    </ul>
                  </div>
                  <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0">
                        Sit erat duo lorem duo ea consetetur, et eirmod
                        takimata.
                      </li>
                      <li class="list-group-item px-0">
                        Amet kasd gubergren sit sanctus et lorem eos sadipscing
                        at.
                      </li>
                      <li class="list-group-item px-0">
                        Duo amet accusam eirmod nonumy stet et et stet eirmod.
                      </li>
                      <li class="list-group-item px-0">
                        Takimata ea clita labore amet ipsum erat justo voluptua.
                        Nonumy.
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="tab-pane-3">
                <div class="row">
                  
                  <div class="col-md-6" >
                   <h4 class="mb-4">{{product.productRatings.length}} review for "{{product.productRatings[0]?.product.title || product.singleProduct.title}}"</h4>
                    <div v-for="rating in product.productRatings" class="media mb-2">
                      <i class="fa fa-user" style="font-size:20px"></i>
                      <div class="media-body" style="margin-left:5px">
                        <h6>
                          {{rating.serviceinfos.name}}<small> - <i>{{moment(rating.created_at).fromNow()}}</i></small>
                        </h6>
                        <div v-if="rating.rating === 1" class="text-primary mb-2">
                          <i class="fas fa-star"></i>
                        </div>
                        <div v-if="rating.rating === 2" class="text-primary mb-2">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div v-if="rating.rating === 3" class="text-primary mb-2">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div v-if="rating.rating === 4" class="text-primary mb-2">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div v-if="rating.rating === 5" class="text-primary mb-2">
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>
                          {{rating.comment}}
                        </p>
                      </div>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <h4 class="mb-4">Leave a review</h4>
                    <div class="rating-system">
                      <div class="stars">
                          <i v-for="star in totalStars" :key="star" class="fa fa-star"
                             :class="star <= currentRating ? 'fa-star text-warning' : 'fa-star-o text-muted'"
                             @mouseover="hoverStar(star)"
                             @mouseleave="resetHover"
                             @click="rates(star)"
                          ></i>
                        </div>
                       <p v-if="showRatingText"> {{ currentRating }} out of {{ totalStars }}</p>
                      </div>
                    <form @submit.prevent="rateProductFn">
                      <div class="form-group">
                        <label for="message">Your Review *</label>
                        <textarea
                        v-model="form.comment"
                          id="message"
                          cols="30"
                          rows="5"
                          class="form-control"
                        ></textarea>
                      </div>
                      
                      <div class="form-group mb-0">
                        <input
                          type="submit"
                          value="Leave Your Review"
                          class="btn btn-primary px-3"
                        />
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Shop Detail End -->

    <!-- Products Start -->
    <div class="container-fluid py-5">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">You May Also Like</span>
      </h2>
      <div class="row px-xl-5">
        <div class="col">
          <div class="owl-carousel related-carousel">
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img
                  class="img-fluid w-100"
                  src="/assets/img/product-1.jpg"
                  alt=""
                />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img
                  class="img-fluid w-100"
                  src="/assets/img/product-2.jpg"
                  alt=""
                />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img
                  class="img-fluid w-100"
                  src="/assets/img/product-3.jpg"
                  alt=""
                />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img
                  class="img-fluid w-100"
                  src="/assets/img/product-4.jpg"
                  alt=""
                />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
            <div class="product-item bg-light">
              <div class="product-img position-relative overflow-hidden">
                <img
                  class="img-fluid w-100"
                  src="/assets/img/product-5.jpg"
                  alt=""
                />
                <div class="product-action">
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-shopping-cart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="far fa-heart"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-sync-alt"></i
                  ></a>
                  <a class="btn btn-outline-dark btn-square" href=""
                    ><i class="fa fa-search"></i
                  ></a>
                </div>
              </div>
              <div class="text-center py-4">
                <a class="h6 text-decoration-none text-truncate" href=""
                  >Product Name Goes Here</a
                >
                <div
                  class="d-flex align-items-center justify-content-center mt-2"
                >
                  <h5>$123.00</h5>
                  <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                </div>
                <div
                  class="d-flex align-items-center justify-content-center mb-1"
                >
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small class="fa fa-star text-primary mr-1"></small>
                  <small>(99)</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Products End -->
  </div>
 
</template>

<style>
.bg-green {
  background-color: #318e2b !important;
}
.bg-red {
  background-color: #8e2b2b !important;
}
.bg-yellow {
  background-color: #ffd333 !important;
}

.text-green {
  color: #318e2b !important;
}
.text-white {
  color: #fcfcfc !important;
}
</style>
