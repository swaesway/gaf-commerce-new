<script setup>
import { reactive } from "vue";
import { useToast } from "vue-toastification";

import { useVendorStore } from "@/stores/vendor";

const toast = useToast();
const { registerFn } = useVendorStore();

const form = reactive({
  shopname: "",
  email: "",
  telephone: "",
  location: "",
  region: "",
  proof_of_business: "",
  password: "",
  password_confirmation: "",
});

function handleFile(event) {
  const file = event.target.files[0];
  if (!file) {
    toast.success("file is required");
    return;
  }

  form.proof_of_business = file;
}

function createVendorAccount() {
  const {
    shopname,
    email,
    telephone,
    location,
    region,
    proof_of_business,
    password,
    password_confirmation,
  } = form;

  const credentials = {
    shopname,
    email,
    telephone,
    location,
    region,
    proof_of_business,
    password,
    password_confirmation,
  };

  registerFn(credentials);

  return;
}
</script>

<template>
  <div>
    <main>
      <div class="container">
        <section
          class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4"
        >
          <div class="container">
            <div class="row justify-content-center">
              <!-- <div v-if="Object.keys(errorlist).length > 0" class="alert alert-danger">
                  <h6>You have errors in your form:</h6>
                  <ul class="mb-0 ps-3">
                    <li v-for="(errors, field) in errorlist" :key="field">
                      <span v-for="error in errors" :key="error">{{ error }}</span>
                    </li>
                  </ul>
                </div>

                <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div> -->

              <div
                class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center"
              >
                <div class="card mb-3">
                  <div class="card-body">
                    <h5 class="card-title text-center pb-0 fs-4">
                      Create an Account
                    </h5>
                    <form
                      class="row g-3 needs-validation"
                      @submit.prevent="createVendorAccount"
                      enctype="multipart/form-data"
                    >
                      <div class="col-12">
                        <label for="shopname" class="form-label"
                          >Shop Name</label
                        >
                        <input
                          type="text"
                          v-model="form.shopname"
                          class="form-control"
                        />
                        <!-- <div class="invalid-feedback" v-if="errorlist.shopname">{{ errorlist.shopname[0] }}</div> -->
                      </div>
                      <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input
                          type="email"
                          v-model="form.email"
                          class="form-control"
                        />
                        <!-- <div class="invalid-feedback" v-if="errorlist.email">{{ errorlist.email[0] }}</div> -->
                      </div>
                      <div class="col-12">
                        <label for="telephone" class="form-label"
                          >Telephone</label
                        >
                        <input
                          type="tel"
                          v-model="form.telephone"
                          class="form-control"
                        />
                        <!-- <div class="invalid-feedback" v-if="errorlist.telephone">{{ errorlist.telephone[0] }}</div> -->
                      </div>
                      <div class="col-12">
                        <label for="location" class="form-label"
                          >Location</label
                        >
                        <input
                          type="text"
                          v-model="form.location"
                          class="form-control"
                        />
                        <!-- <div class="invalid-feedback" v-if="errorlist.location">{{ errorlist.location[0] }}</div> -->
                      </div>
                      <div class="col-12">
                        <label for="region" class="form-label">Region</label>
                        <select
                          v-model="form.region"
                          class="form-select"
                          id="region"
                        >
                          <option value="">Select a region</option>
                          <option value="Ahafo">Ahafo Region</option>
                          <option value="Ashanti">Ashanti Region</option>
                          <option value="Bono">Bono Region</option>
                          <option value="Bono East">Bono East Region</option>
                          <option value="Central">Central Region</option>
                          <option value="Eastern">Eastern Region</option>
                          <option value="Greater Accra">
                            Greater Accra Region
                          </option>
                          <option value="North East">North East Region</option>
                          <option value="Northern">Northern Region</option>
                          <option value="Oti">Oti Region</option>
                          <option value="Savannah">Savannah Region</option>
                          <option value="Upper East">Upper East Region</option>
                          <option value="Upper West">Upper West Region</option>
                          <option value="Volta">Volta Region</option>
                          <option value="Western">Western Region</option>
                          <option value="Western North">
                            Western North Region
                          </option>
                        </select>
                        <!-- <div class="invalid-feedback" v-if="errorlist.region">{{ errorlist.region[0] }}</div> -->
                      </div>

                      <div class="col-12">
                        <label for="proof_of_business" class="form-label"
                          >Upload Proof Of Business</label
                        >
                        <input
                          type="file"
                          @change="handleFile"
                          accept=".pdf,.png,.jpg,.jpeg"
                          class="form-control"
                          id="proof_of_business"
                        />
                        <div class="form-text">
                          Accepted formats: PDF, PNG, JPG (Max size: 5MB)
                        </div>
                      </div>
                      <div class="col-12">
                        <label for="password" class="form-label"
                          >Password</label
                        >
                        <input
                          type="password"
                          v-model="form.password"
                          class="form-control"
                        />
                        <!-- <div class="invalid-feedback" v-if="errorlist.password">{{ errorlist.password[0] }}</div> -->
                      </div>
                      <div class="col-12">
                        <label for="password_confirmation" class="form-label"
                          >Confirm Password</label
                        >
                        <input
                          type="password"
                          v-model="form.password_confirmation"
                          class="form-control"
                        />
                      </div>
                      <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">
                          Create Account
                        </button>
                      </div>
                      <div class="col-12">
                        <p class="small mb-0">
                          Already have an account?
                          <router-link to="/vendor/login">Log in</router-link>
                        </p>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<!-- <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        registervendor: {
          shopname: '',
          email: '',
          telephone: '',
          location: '',
          region: '',
          password: '',
          password_confirmation: ''
        },
        errorlist: {},
        successMessage: ""
      };
    },
    methods: {
      CreateVendorAccount() {
        axios
          .post('http://127.0.0.1:8000/api/vendor/register', this.registervendor)
          .then((res) => {
            this.successMessage = res.data.message; 
            this.errorlist = {};
            this.registervendor = {
              shopname: '',
              email: '',
              telephone: '',
              location: '',
              region: '',
              password: '',
              password_confirmation: ''
            };
            this.errorlist = {};
          })
          .catch((error) => {
            console.log(error)
            if (error.response && error.response.status === 400) {
              this.errorlist = error.response.data;
            }
          });
      }
    }
  };
  </script> -->
