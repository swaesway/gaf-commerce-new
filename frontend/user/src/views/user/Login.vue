<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";

import { useToast } from "vue-toastification";

import { userStore } from "../../stores/user";


const router = useRouter();
const { loginFn } = userStore();

const form = reactive({
  servicenumber: "",
  telephone: ""
});

function loginUser() {
  const { servicenumber, telephone  } = form;
  const credentials = { servicenumber, telephone };
  loginFn(credentials, router);
}




</script>

<template lang="">
  <div>
    <section
      class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4"
    >
      <div class="container">
        <div class="row justify-content-center">
          <div
            class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center"
          >
            <!-- End Logo -->

            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">
                    Login to Your Account
                  </h5>
                  <p class="text-center small">
                    Enter your servicenumber & phonenumber to login
                  </p>
                </div>

                <form class="row g-3 needs-validation" @submit.prevent="loginUser">
                  <div class="col-12">
                    <label for="yourServicenumber" class="form-label">Sevicenumber</label>
                    <div class="input-group has-validation">
                      <input
                       v-model="form.servicenumber"
                        type="text"
                        name="servicenumber"
                        class="form-control"
                        id="yourServicenumber"
                      
                      />
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yourPhonenumber" class="form-label"
                      >Phonenumber</label
                    >
                    <input
                    v-model="form.telephone"
                      type="tel"
                      name="phonenumber"
                      class="form-control"
                      id="yourPhonenumber"
                      
                    />
                  </div>
                  <div class="col-12 mt-3 mb-2">
                    <button
                      class="btn btn-primary w-100"
                      type="submit"
                      name="loginbtnuser"
                    >
                      Login
                    </button>
                  </div>
                  <div class="col-12">
                    <!-- <p class="small mb-0">
                      Don't have account?
                      <a href="register.php">Create an account</a>
                    </p> -->
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>



<style>
.section.register {
  background-image: url("/assets/img/gafbg.jpg"); /* Replace with your image path */
  background-size: cover; /* Ensures the image covers the entire page */
  background-position: top center; /* Keeps the image centered horizontally and aligned to the top */
  background-repeat: repeat; /* Prevents image repetition */
  background-attachment: fixed; /* Optional: Creates a parallax scrolling effect */
}
</style>