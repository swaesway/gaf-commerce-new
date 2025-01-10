<script setup>

import { reactive } from 'vue';
import { useRouter } from 'vue-router';

import { userStore } from '@/stores/user';

const router = useRouter();
const { verifyTokenFn } = userStore();

const form = reactive({
  toke: ""
});

function verifyToken(){
  const { token } = form;
  verifyTokenFn({token}, router);
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
            class="col-lg-6 col-md-6 d-flex flex-column align-items-center justify-content-center"
          >
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">
                    Verify to continue
                  </h5>
                  <p class="text-center small">
                    Enter your received token, sent to ...
                  </p>
                </div>

                <form class="row g-3 needs-validation" @submit.prevent="verifyToken">
                  <div class="col-12">
                    <input
                    v-model="form.token"
                      type="text"
                      name="token"
                      class="form-control"
                      id="yourToken"
                     
                    />
                  </div>  

                  <div class="col-12 mt-3">
                    <button
                      class="btn btn-primary w-100"
                      name="registerAccountBtnUser"
                      type="submit"
                    >
                      Confirm Verification
                    </button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">
                      Resend token? <a href="login.php">Log in</a>
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
</template>
