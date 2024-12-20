<template lang="">
  <div>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-12">
          <nav class="breadcrumb bg-light mb-30">
            <a class="breadcrumb-item text-dark" href="/">Home</a>
            <span class="breadcrumb-item active">Wishlist</span>
          </nav>
        </div>
      </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Cart Start -->
    <div class="container-fluid">
      <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
          <table
            class="table table-light table-borderless table-hover text-center mb-0"
          >
            <thead class="thead-dark">
              <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Review</th>
                <th>Category</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="align-middle">
              <tr>
                <td class="align-middle">
                  <img
                    src="/assets/img/product-1.jpg"
                    alt=""
                    style="width: 50px"
                  />
                  Product Name
                </td>
                <td class="align-middle">₵150</td>
                <td class="align-middle">
                  <small
                    v-for="n in 5"
                    :key="n"
                    class="fa fa-star text-primary mr-1"
                  ></small>
                </td>
                <td class="align-middle">Electronics</td>
                <td class="align-middle">
                  <button class="btn btn">
                    <i class="fa fa-heart text-primary"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  <img
                    src="/assets/img/product-2.jpg"
                    alt=""
                    style="width: 50px"
                  />
                  Product Name
                </td>
                <td class="align-middle">₵150</td>
                <td class="align-middle">
                  <small
                    v-for="n in 5"
                    :key="n"
                    class="fa fa-star text-primary mr-1"
                  ></small>
                </td>
                <td class="align-middle">Clothes</td>
                <td class="align-middle">
                  <button class="btn btn">
                    <i class="fa fa-heart text-primary"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  <img
                    src="/assets/img/product-3.jpg"
                    alt=""
                    style="width: 50px"
                  />
                  Product Name
                </td>
                <td class="align-middle">₵150</td>
                <td class="align-middle">
                  <small
                    v-for="n in 5"
                    :key="n"
                    class="fa fa-star text-primary mr-1"
                  ></small>
                </td>
                <td class="align-middle">Electronics</td>
                <td class="align-middle">
                  <button class="btn btn">
                    <i class="fa fa-heart text-primary"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  <img
                    src="/assets/img/product-4.jpg"
                    alt=""
                    style="width: 50px"
                  />
                  Product Name
                </td>
                <td class="align-middle">₵150</td>
                <td class="align-middle">
                  <small
                    v-for="n in 5"
                    :key="n"
                    class="fa fa-star text-primary mr-1"
                  ></small>
                </td>
                <td class="align-middle">Footwear</td>
                <td class="align-middle">
                  <button class="btn btn">
                    <i class="fa fa-heart text-primary"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td class="align-middle">
                  <img
                    src="/assets/img/product-5.jpg"
                    alt=""
                    style="width: 50px"
                  />
                  Product Name
                </td>
                <td class="align-middle">₵150</td>
                <td class="align-middle">
                  <small
                    v-for="n in 5"
                    :key="n"
                    class="fa fa-star text-primary mr-1"
                  ></small>
                </td>
                <td class="align-middle">Electronics</td>
                <td class="align-middle">
                  <button class="btn btn">
                    <i class="fa fa-heart text-primary"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-lg-4">
          <!-- Color Start -->
          <h5 class="section-title position-relative text-uppercase mb-3">
            <span class="bg-secondary pr-3">Filter by category</span>
          </h5>
          <div class="bg-light p-4 mb-30">
            <form>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  checked
                  id="color-all"
                />
                <label class="custom-control-label" for="price-all"
                  >All category</label
                >
                <span class="badge border font-weight-normal">1000</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-1"
                />
                <label class="custom-control-label" for="color-1"
                  >Uniforms</label
                >
                <span class="badge border font-weight-normal">150</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-2"
                />
                <label class="custom-control-label" for="color-2"
                  >Clothes</label
                >
                <span class="badge border font-weight-normal">295</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-3"
                />
                <label class="custom-control-label" for="color-3"
                  >Electronics</label
                >
                <span class="badge border font-weight-normal">246</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-1"
                />
                <label class="custom-control-label" for="color-1"
                  >Cosmetics</label
                >
                <span class="badge border font-weight-normal">150</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-2"
                />
                <label class="custom-control-label" for="color-2"
                  >Footwear</label
                >
                <span class="badge border font-weight-normal">295</span>
              </div>
              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-4"
                />
                <label class="custom-control-label" for="color-4"
                  >Headgear</label
                >
                <span class="badge border font-weight-normal">145</span>
              </div>

              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-2"
                />
                <label class="custom-control-label" for="color-2"
                  >Books & Stationary</label
                >
                <span class="badge border font-weight-normal">295</span>
              </div>

              <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  id="color-5"
                />
                <label class="custom-control-label" for="color-5"
                  >Food & Beverages</label
                >
                <span class="badge border font-weight-normal">168</span>
              </div>
            </form>
          </div>
          <!-- Color End -->
        </div>
      </div>
    </div>
    <!-- Cart End -->
  </div>
</template>
<script>
export default {};
</script>
<style lang=""></style>
