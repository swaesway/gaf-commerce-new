<script setup>
import { onMounted, reactive } from 'vue';
import { userStore } from '@/stores/user';
import { useRouter } from 'vue-router';

import ClipLoader  from 'vue-spinner/src/ClipLoader.vue';

const router = useRouter();
const {store,  getWishlist, contactAdmin, resetIsMailSent } = userStore();

const form = reactive({
  subject: "",
  message: ""
})

function contactAdminFn(){
  contactAdmin({subject: form.subject, message: form.message}, router);
  form.subject = "";
  form.message = "";
  
}

onMounted(() => {
  getWishlist(router);
})


</script>


<template lang="">
  <div>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav class="breadcrumb bg-light mb-30">
            <a class="breadcrumb-item text-dark" href="#">Home</a>
            <span class="breadcrumb-item active">Contact</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Contact Start -->
    <div class="container-fluid">
      <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Contact Us</span>
      </h2>
      <div class="row px-xl-5">
        <div class="col-lg-7 mb-5">
          <div class="contact-form bg-light p-30">
            <div id="success"></div>
            <form name="sentMessage" id="contactForm" novalidate="novalidate" @submit.prevent="contactAdminFn">
              <div class="control-group">
                <input
                  v-model="form.subject"
                  type="text"
                  class="form-control"
                  id="subject"
                  placeholder="Subject"
                  required="required"
                  data-validation-required-message="Please enter a subject"
                />
                <!-- <p class="help-block text-danger"></p> -->
              </div>
              <br />
              <div class="control-group">
                <textarea
                   v-model="form.message"
                  class="form-control"
                  rows="8"
                  id="message"
                  placeholder="Message"
                  required="required"
                  data-validation-required-message="Please enter your message"
                ></textarea>
                <!-- <p class="help-block text-danger"></p> -->
              </div>
              <div v-if="store.isMailSent">
                <button
                  class="btn btn-primary py-2 px-4"
                  type="submit"
                  id="sendMessageButton"
                  disabled
                >
                
                  Send Message

                  <ClipLoader :loading="true" :color="'rgb(204 208 207)'" style="float:right;" />
                </button>
              </div>
              <div v-else>
                <button
                  class="btn btn-primary py-2 px-4"
                  type="submit"
                  id="sendMessageButton"
                >
                  Send Message
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-5 mb-5">
          <div class="bg-light p-30 mb-30">
            <iframe
              style="width: 100%; height: 250px; border: 0"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7352.946045469869!2d-0.15802466745552732!3d5.594975782627448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf9ad1f0efd137%3A0xbba2bc1bac87124e!2sBurma%20Camp%2C%20Accra!5e0!3m2!1sen!2sgh!4v1734699045651!5m2!1sen!2sgh"
              frameborder="0"
              allowfullscreen=""
              aria-hidden="false"
              tabindex="0"
            ></iframe>
          </div>
          <div class="bg-light p-30 mb-3">
            <p class="mb-2">
              <i class="fa fa-map-marker-alt text-primary mr-3"></i>Burma Camp,
              Accra
            </p>
            <p class="mb-2">
              <i class="fa fa-envelope text-primary mr-3"></i>info@example.com
            </p>
            <p class="mb-2">
              <i class="fa fa-phone-alt text-primary mr-3"></i>+233 000 000 000
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- Contact End -->
  </div>
</template>


<style lang=""></style>
