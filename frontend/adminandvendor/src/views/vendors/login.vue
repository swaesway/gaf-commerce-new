<script setup>
import { reactive, onBeforeMount } from 'vue';
import { useToast } from 'vue-toastification';

import { useVendorStore } from "@/stores/vendor";

import ClipLoader  from 'vue-spinner/src/ClipLoader.vue';

const { vendor,  loginFn  } = useVendorStore();

const form = reactive({
 email: "",
 password: "",
});


function loginVendor(){
  const { email, password } = form;
  const credentials = {email, password}
  
   loginFn(credentials);

  return;

}




</script>
<template>
    <div>
        <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <!-- <div v-if="Object.keys(errorlist).length > 0" class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        <li v-for="(errors, field) in errorlist" :key="field">
                            <span v-for="error in errors" :key="error">{{ error }}</span>
                        </li>
                    </ul>

                </div>

                <div v-if="SuccessMessage" class="alert alert-primary">
                    {{ SuccessMessage }}
                </div> -->

              <div class="d-flex justify-content-center py-4">
                <a href="/" class="logo d-flex align-items-center w-auto">
                  <img src="/assets/img/logo.png" alt="">
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form class="row g-3 needs-validation" @submit.prevent="loginVendor" novalidate>

                    <div class="col-12">
                      <label for="email" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <input type="text" name="email" class="form-control" v-model="form.email">
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="Password" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="Password" v-model="form.password">
                    </div>

                    <div class="col-12">
                      <button v-if="vendor.isLoading" class="btn btn-primary w-100" type="submit" disabled>Login <ClipLoader :loading="true" :color="'rgb(204 208 207)'" style="float:right;" /></button>
                      <button v-else class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <router-link to="/vendor/register">Create an account</router-link></p>
                    </div>
                  </form>

                </div>
              </div>

           

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->


    </div>
</template>

<!-- <script>
import axios from 'axios';
import { useToast } from 'vue-toastification';

const toast = useToast();

export default{
    data(){
        return{
            loginvendor: {
                email: '',
                password: ''
            },
            errorlist: {},
            SuccessMessage: ''
        };
    },
    methods: {
        ValidateVendor()
        {
            this.SuccessMessage = '';
            axios.post('http://127.0.0.1:8000/api/vendor/login', this.loginvendor)
            .then((res) => {
                this.SuccessMessage = "logged in succesfully",
                this.errorlist = {};
                this.loginvendor = {
                    email: '',
                    password: ''
                };
             
            }).catch((error) => {
                
              console.log(error)
                if(error.response && error.response.status === 401){
                    this.errorlist = error.response.data;
                }

            });
        }
    }
}

</script> -->