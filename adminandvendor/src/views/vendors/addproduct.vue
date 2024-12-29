<template>
    <div>
      <div class="pagetitle">
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item">Products</li>
            <li class="breadcrumb-item active">Add a Product</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->
  
      <section class="section">
        <div class="px-1 mt-n1">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-9">
              <div class="card add-product-card">
                <h5 class="text-center mb-4">Product Upload</h5>
                <form class="form-card" @submit.prevent="uploadProduct">
                  <!-- Product Name and Price -->
                  <div class="row justify-content-between text-left mb-4">
                    <div class="form-floating col-sm-6 mb-3 col-12">
                      <input
                        type="text"
                        class="form-control"
                        v-model="product.title"
                        required
                      />
                      <label for="productName">Title*</label>
                      <small class="text-danger" v-if="errors.title">{{ errors.title }}</small>
                    </div>
                    <div class="form-floating col-sm-6">
                      <input
                        type="number"
                        class="form-control"
                        v-model="product.price"
                        required
                      />
                      <label for="productPrice">Price*</label>
                      <small class="text-danger" v-if="errors.price">{{ errors.price }}</small>
                    </div>
                  </div>
  
                  <!-- Category Dropdown -->
                  <div class="row justify-content-between text-left mb-4">
                    <div class="form-floating col-12">
                      <select
                        class="form-select"
                        v-model="product.category"
                        required
                      >
                        <option value="" disabled>Select a Category</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Footwear">Footwear</option>
                        <option value="Food Stuffs">Food Stuffs</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Grocery">Grocery</option>
                        <option value="Home Appliances">Home Appliances</option>
                      </select>
                      <label for="productCategory">Category*</label>
                      <small class="text-danger" v-if="errors.category">{{ errors.category }}</small>
                    </div>
                  </div>
  
                  <!-- Product Description -->
                  <div class="row justify-content-between text-left mb-4">
                    <div class="form-floating col-12">
                      <textarea
                        v-model="product.description"
                        class="form-control"
                        rows="3"
                        required
                      ></textarea>
                      <label for="productDescription">Description*</label>
                      <small
                        class="text-danger"
                        v-if="errors.description"
                      >{{ errors.description }}</small>
                    </div>
                  </div>
  
                  <!-- File Upload Button with Max 4 Images -->
                  <div class="form-group mb-4">
                    <h6 class="fw-bold mb-2">Add up to 4 photos for this category</h6>
                    <button
                      type="button"
                      class="btn btn-outline-success"
                      @click="triggerFileUpload"
                    >
                      <i>+</i>
                    </button>
                    <input
                      type="file"
                      ref="fileInput"
                      class="d-none"
                      multiple
                      accept="image/*"
                      @change="handleFileUpload"
                    />
                    <small class="text-danger" v-if="errors.files">{{ errors.files }}</small>
                    <div id="preview-container" class="mt-3 d-flex flex-wrap">
                      <img
                        v-for="(img, index) in previewImages"
                        :key="index"
                        :src="img"
                        class="img-thumbnail"
                        :alt="'Preview ' + (index + 1)"
                      />
                    </div>
                  </div>
  
                  <!-- Submit Button -->
                  <div class="row justify-content-center text-center">
                    <div class="col-sm-6">
                      <button type="submit" class="btn btn-success" style="width: 100%">
                        Upload Product
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        product: {
          title: "",
          price: "",
          category: "",
          description: "",
          images: [],
        },
        previewImages: [],
        errors: {},
      };
    },
    methods: {
      triggerFileUpload() {
        this.$refs.fileInput.click();
      },
      handleFileUpload(event) {
        const files = event.target.files;
        if (files.length > 4) {
          this.errors.files = "You can upload up to 4 images.";
          this.previewImages = [];
          return;
        }
        this.errors.files = "";
        this.product.images = Array.from(files);
        this.previewImages = this.product.images.map((file) => URL.createObjectURL(file));
      },
      uploadProduct() {
        // Clear previous errors
        this.errors = {};
  
        // Basic validation
        if (!this.product.title) this.errors.title = "Title is required.";
        if (!this.product.price) this.errors.price = "Price is required.";
        if (!this.product.category) this.errors.category = "Category is required.";
        if (!this.product.description) this.errors.description = "Description is required.";
        if (this.product.images.length === 0)
          this.errors.files = "Please upload at least one image.";
  
        // If there are errors, stop submission
        if (Object.keys(this.errors).length > 0) return;
  
        // Prepare form data
        const formData = new FormData();
        formData.append("title", this.product.title);
        formData.append("price", this.product.price);
        formData.append("category", this.product.category);
        formData.append("description", this.product.description);
        this.product.images.forEach((file, index) => {
          formData.append(`images[${index}]`, file);
        });
  
        // Send data to the backend
        this.$axios
          .post("/api/upload-product", formData, {
            headers: { "Content-Type": "multipart/form-data" },
          })
          .then((response) => {
            alert("Product uploaded successfully!");
            this.resetForm();
          })
          .catch((error) => {
            console.error("Error uploading product:", error);
            alert("There was an error uploading the product.");
          });
      },
      resetForm() {
        this.product = { title: "", price: "", category: "", description: "", images: [] };
        this.previewImages = [];
      },
    },
  };
  </script>
  
  <style scoped>
  .add-product-card {
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  #preview-container img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin: 5px;
    border-radius: 5px;
  }
  </style>
  