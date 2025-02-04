<script setup>
import { reactive, ref, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useVendorStore } from "@/stores/vendor";
import axiosPrivate from "@/api/axiosPrivate";
import Quill from "quill";
import "quill/dist/quill.snow.css";

import ClipLoader  from 'vue-spinner/src/ClipLoader.vue';

const {vendor,  addProduct } = useVendorStore();
const toast = useToast();

const product = reactive({
  title: "",
  price: "",
  category: "",
  description: "",
  images: [],
});

const previewImages = ref([]);
const fileInput = ref(null);
const quillEditor = ref(null);

function triggerFileUpload() {
  fileInput.value.click();
}

function handleFileUpload(event) {
  const files = event.target.files;
  if (files.length > 4) {
    toast.error("You can upload up to 4 images.");
    previewImages.value = [];
    return;
  }

  product.images = Array.from(files);
  previewImages.value = product.images.map((file) => URL.createObjectURL(file));
}

function resetForm() {
  product.title = "";
  product.price = "";
  product.category = "";
  product.description = "";
  product.images = [];

  previewImages.value = [];
  quillEditor.value.setContents([]); // Clear the Quill editor
}

function handlePriceInput(event) {
  const input = event.target.value;

  const validInput = input.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");

  product.price = validInput;
}

function uploadProduct() {
  product.description = quillEditor.value.root.innerHTML; // Get content from the Quill editor

  const data = {
    title: product.title,
    price: product.price,
    category: product.category,
    description: product.description,
  };

  addProduct(data, product.images);
}

function onDeleteImage(index) {
  product.images.splice(index, 1);
  previewImages.value.splice(index, 1);
}

onMounted(() => {
  axiosPrivate.get("/verify/token");

  // Initialize Quill editor
  quillEditor.value = new Quill("#quill-editor", {
    theme: "snow",
    placeholder: "Enter product description...",
    modules: {
      toolbar: [
        ["bold", "italic", "strike"], // Formatting buttons
        [{ list: "ordered" }, { list: "bullet" }],
        [{ header: [false] }], // Header levels
        ["link"], // Insert link or image
      ],
    },
  });
});
</script>

<template>
  <div>
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/vendor/">Dashboard</a></li>
          <li class="breadcrumb-item">Products</li>
          <li class="breadcrumb-item active">Add a Product</li>
        </ol>
      </nav>
    </div>

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
                    />
                    <label for="productName">Title*</label>
                  </div>
                  <div class="form-floating col-sm-6">
                    <input
                      type="text"
                      class="form-control"
                      v-model="product.price"
                      @input="handlePriceInput"
                    />
                    <label for="productPrice">Price*</label>
                  </div>
                </div>

                <!-- Category Dropdown -->
                <div class="row justify-content-between text-left mb-4">
                  <div class="form-floating col-12">
                    <select class="form-select" v-model="product.category">
                      <option value="" disabled>Select a Category</option>
                      <option value="Clothes">Clothes</option>
                      <option value="Electronics">Electronics</option>
                      <option value="Cosmetics">Cosmetics</option>
                      <option value="Footwear">Footwear</option>
                      <option value="Headgear">Headgear</option>
                      <option value="Books & Stationary">
                        Books & Stationary
                      </option>
                      <option value="Food & Beverages">Food & Beverages</option>
                    </select>
                    <label for="productCategory">Category*</label>
                  </div>
                </div>

                <!-- Rich Text Editor for Product Description -->
                <div class="row justify-content-between text-left mb-4">
                  <div class="col-12">
                    <label for="productDescription" class="form-label">
                      Description*
                    </label>
                    <div id="quill-editor" style="height: 200px;"></div>
                  </div>
                </div>

                <!-- File Upload Button -->
                <div class="form-group mb-4">
                  <h6 class="fw-bold mb-2">
                    Add up to 4 photos for this category
                  </h6>
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
                  <div id="preview-container" class="mt-3 d-flex flex-wrap">
                    <div
                      v-for="(img, index) in previewImages"
                      :key="index"
                      class="position-relative me-2 mb-2"
                      style="display: inline-block"
                    >
                      <img
                        :src="img"
                        class="img-thumbnail"
                        :alt="'Preview ' + (index + 1)"
                      />
                      <button
                        type="button"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                        @click="onDeleteImage(index)"
                      >
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="row justify-content-center text-center">
                  <div class="col-sm-6">
                    <button v-if="vendor.isLoading"
                      type="submit"
                      class="btn btn-success"
                      style="width: 100%"
                      disabled
                    >
                      Upload Product
                      <ClipLoader :loading="true" :color="'rgb(204 208 207)'" style="float:right;" />
                    </button>

                    <button v-else
                      type="submit"
                      class="btn btn-success"
                      style="width: 100%"
                    >
                      Upload Product
                      <!-- <ClipLoader :loading="true" :color="'rgb(204 208 207)'" style="float:right;" /> -->
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
