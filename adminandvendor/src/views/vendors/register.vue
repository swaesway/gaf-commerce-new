<template>
    <div>
      <main>
        <div class="container">
          <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
              <div class="row justify-content-center">
                <div v-if="Object.keys(errorlist).length > 0" class="alert alert-danger">
                  <h6>You have errors in your form:</h6>
                  <ul class="mb-0 ps-3">
                    <li v-for="(errors, field) in errorlist" :key="field">
                      <span v-for="error in errors" :key="error">{{ error }}</span>
                    </li>
                  </ul>
                </div>

                <div v-if="successMessage" class="alert alert-success">
                {{ successMessage }}
              </div>
  
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                  <div class="card mb-3">
                    <div class="card-body">
                      <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                      <form class="row g-3 needs-validation" @submit.prevent="CreateVendorAccount">
                        <div class="col-12">
                          <label for="shopname" class="form-label">Shop Name</label>
                          <input type="text" v-model="registervendor.shopname" class="form-control" required>
                          <div class="invalid-feedback" v-if="errorlist.shopname">{{ errorlist.shopname[0] }}</div>
                        </div>
                        <div class="col-12">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" v-model="registervendor.email" class="form-control" required>
                          <div class="invalid-feedback" v-if="errorlist.email">{{ errorlist.email[0] }}</div>
                        </div>
                        <div class="col-12">
                          <label for="telephone" class="form-label">Telephone</label>
                          <input type="tel" v-model="registervendor.telephone" class="form-control" minlength="10" required>
                          <div class="invalid-feedback" v-if="errorlist.telephone">{{ errorlist.telephone[0] }}</div>
                        </div>
                        <div class="col-12">
                          <label for="location" class="form-label">Location</label>
                          <input type="text" v-model="registervendor.location" class="form-control" required>
                          <div class="invalid-feedback" v-if="errorlist.location">{{ errorlist.location[0] }}</div>
                        </div>
                        <div class="col-12">
                          <label for="region" class="form-label">Region</label>
                          <input type="text" v-model="registervendor.region" class="form-control" required>
                          <div class="invalid-feedback" v-if="errorlist.region">{{ errorlist.region[0] }}</div>
                        </div>
                        <div class="col-12">
                          <label for="password" class="form-label">Password</label>
                          <input type="password" v-model="registervendor.password" class="form-control" required>
                          <div class="invalid-feedback" v-if="errorlist.password">{{ errorlist.password[0] }}</div>
                        </div>
                        <div class="col-12">
                          <label for="password_confirmation" class="form-label">Confirm Password</label>
                          <input type="password" v-model="registervendor.password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12">
                          <button class="btn btn-primary w-100" type="submit">Create Account</button>
                        </div>
                        <div class="col-12">
                      <p class="small mb-0">Already have an account? <router-link to="/vendorlogin">Log in</router-link></p>
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
  
  <script>
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
            if (error.response && error.response.status === 400) {
              this.errorlist = error.response.data;
            }
          });
      }
    }
  };
  </script>
  