<script></script>
<template>
  <div>
    <div class="container-fluid">
      <div
        class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex"
      >
        <div class="col-lg-4">
          <a href="/" class="text-decoration-none poppins-bold text-green">
            <!-- <span class="h1 text-uppercase text-primary bg-green px-2">GAF</span>
            <span class="h1 text-uppercase text-green bg-primary px-2 ml-n1"
              >MARKET</span
            > -->

            GAF MARKET
          </a>
        </div>
        <div class="col-lg-4 col-6 text-left">
          <form action="">
            <div class="input-group" style="width: 150%">
              <input
                type="text"
                class="form-control rounded-pill"
                placeholder="Search for products"
                style="
                  height: 50px;
                  border-radius: 50px;
                  padding-right: 40px;
                  width: 100%;
                "
              />
              <div class="input-group-append">
                <span
                  class="input-group-text bg-transparent text-primary border-0"
                >
                  <i class="fa fa-search"></i>
                </span>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-4 col-6 text-right">
          <div class="btn-group">
            <button
              type="button"
              class="btn btn-sm btn-light dropdown-toggle"
              data-toggle="dropdown"
            >
              My Account
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="/login"
                ><button class="dropdown-item" type="button">Sign in</button></a
              >
              <a href="/register"
                ><button class="dropdown-item" type="button">Sign up</button></a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid bg-green mb-30">
      <div class="row px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
          <a
            class="btn d-flex align-items-center justify-content-between bg-primary w-100"
            data-toggle="collapse"
            href="#navbar-vertical"
            style="height: 65px; padding: 0 30px"
          >
            <h6 class="text-dark m-0">
              <i class="fa fa-bars mr-2"></i>Categories
            </h6>
            <i class="fa fa-angle-down text-dark"></i>
          </a>
          <nav
            class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
            id="navbar-vertical"
            style="width: calc(100% - 30px); z-index: 999"
          >
            <div class="navbar-nav w-100">
              <div class="nav-item dropdown dropright">
                <a
                  href="/shop"
                  class="nav-link dropdown-toggle"
                  data-toggle="dropdown"
                  >Uniforms <i class="fa fa-angle-right float-right mt-1"></i
                ></a>
                <div
                  class="dropdown-menu position-absolute rounded-0 border-0 m-0"
                >
                  <a href="/shop" class="dropdown-item">Army</a>
                  <a href="/shop" class="dropdown-item">Navy</a>
                  <a href="/shop" class="dropdown-item">Air Forces</a>
                </div>
              </div>
              <a href="" class="nav-item nav-link">Clothes</a>
              <a href="" class="nav-item nav-link">Electronics</a>
              <a href="" class="nav-item nav-link">Cosmetics</a>
              <a href="" class="nav-item nav-link">Footwear</a>
              <a href="" class="nav-item nav-link">Headgear</a>
              <a href="" class="nav-item nav-link">Books & Stationery</a>
              <a href="" class="nav-item nav-link">Food & Beverages</a>
            </div>
          </nav>
        </div>
        <div class="col-lg-9">
          <nav
            class="navbar navbar-expand-lg bg-green navbar-dark py-3 py-lg-0 px-0"
          >
            <a href="" class="text-decoration-none d-block d-lg-none">
              <span class="h1 text-uppercase text-dark bg-light px-2">GAF</span>
              <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1"
                >Market</span
              >
            </a>
            <button
              type="button"
              class="navbar-toggler"
              data-toggle="collapse"
              data-target="#navbarCollapse"
            >
              <span class="navbar-toggler-icon"></span>
            </button>
            <div
              class="collapse navbar-collapse justify-content-between"
              id="navbarCollapse"
            >
              <div class="navbar-nav mr-auto py-0">
                <a href="/" class="nav-item nav-link active">Home</a>
                <a href="/shop" class="nav-item nav-link">Products</a>
                <a href="/wishlist" class="nav-item nav-link">Wishlist</a>
                <a href="/contact" class="nav-item nav-link">Contact</a>
              </div>
              <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                <a href="/wishlist" class="btn px-0">
                  <i class="fas fa-heart text-primary"></i>
                  <span
                    class="badge text-secondary border border-secondary rounded-circle"
                    style="padding-bottom: 2px"
                    >0</span
                  >
                </a>
                <a href="/chat" class="btn px-0 ml-3">
                  <i class="fas fa-comment text-primary"></i>
                  <span
                    class="badge text-secondary border border-secondary rounded-circle"
                    style="padding-bottom: 2px"
                    >0</span
                  >
                </a>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.bg-green {
  background-color: #318e2b !important;
}

.text-green {
  color: #318e2b !important;
}

.poppins-bold {
  font-family: "Poppins", serif;
  font-size: 2.5rem;
  font-weight: 700;
  font-style: normal;
}

.input-group {
  max-width: 700px;
  margin: auto;
}

.input-group-text {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  border: none;
  background: none;
  cursor: pointer;
}
</style>
