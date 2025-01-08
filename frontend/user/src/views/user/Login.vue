<style>
.section.register {
  background-image: url("/assets/img/gafbg.jpg"); /* Replace with your image path */
  background-size: cover; /* Ensures the image covers the entire page */
  background-position: top center; /* Keeps the image centered horizontally and aligned to the top */
  background-repeat: repeat; /* Prevents image repetition */
  background-attachment: fixed; /* Optional: Creates a parallax scrolling effect */
}
</style>

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
                    Enter your email & password to login
                  </p>
                </div>

                <form class="row g-3 needs-validation">
                  <div class="col-12">
                    <label for="yourUsername" class="form-label">Email</label>
                    <div class="input-group has-validation">
                      <input
                        type="email"
                        name="email"
                        class="form-control"
                        id="yourUsername"
                        required
                      />
                      <div class="invalid-feedback">
                        Please enter your Email.
                      </div>
                    </div>
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
                    <p class="small mb-0">
                      Don't have account?
                      <a href="register.php">Create an account</a>
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
