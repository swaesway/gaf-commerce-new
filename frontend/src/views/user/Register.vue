<script>
export default {};
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
                    Create an Account
                  </h5>
                  <p class="text-center small">
                    Enter your personal details to create account
                  </p>
                </div>

                <form class="row g-3 needs-validation" method="post">
                  <div class="col-12">
                    <label for="yourName" class="form-label">Your Name</label>
                    <input
                      type="text"
                      name="name"
                      class="form-control"
                      id="yourName"
                      required
                    />
                    <div class="invalid-feedback">Please, enter your name!</div>
                  </div>

                  <div class="col-12">
                    <label for="yourEmail" class="form-label">Your Email</label>
                    <input
                      type="email"
                      name="email"
                      class="form-control"
                      id="yourEmail"
                      required
                    />
                    <div class="invalid-feedback">
                      Please enter a valid Email address!
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yourTelephone" class="form-label"
                      >Your Telephone</label
                    >
                    <input
                      type="tel"
                      name="tel"
                      class="form-control"
                      id="yourTelephone"
                      required
                    />
                  </div>

                  <div class="col-12">
                    <label for="yourPassword" class="form-label"
                      >Password</label
                    >
                    <input
                      type="password"
                      name="password"
                      class="form-control"
                      id="yourPassword"
                      required
                    />
                    <div class="invalid-feedback">
                      Please enter your password!
                    </div>
                  </div>

                  <div class="col-12">
                    <button
                      class="btn btn-primary w-100"
                      name="registerAccountBtnUser"
                      type="submit"
                    >
                      Create Account
                    </button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">
                      Already have an account? <a href="login.php">Log in</a>
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
