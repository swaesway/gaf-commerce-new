import { reactive } from "vue";
import { defineStore } from "pinia";

import axiosInstance from "@/api/axiosInstance";

import CryptoJS from "crypto-js";
import { useToast } from "vue-toastification";
import axiosPrivate from "@/api/axiosPrivate";

export const productStore = defineStore("product", () => {
  /*
 states
*/

  const toast = useToast();

  const product = reactive({
    all_product: [],
    singleProduct: {},
    latestProduct: [],
    filteredProduct: [],
    productRatings: [],
    isLoading: true,
  });

  async function getAllProduct() {
    try {
      const response = await axiosInstance.get("/products-all");

      if (response.data && response.status === 200) {
        product.all_product = response.data;
        product.isLoading = false;
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error.";
      }
    }
  }

  async function getProductById(productId) {
    try {
      product.isLoading = true;
      const response = await axiosInstance.get(`/product/single/${productId}`);
      if (response.data && response.status === 200) {
        product.isLoading = false;
        product.singleProduct = response.data;
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else if (
        err?.response?.status === 400 ||
        err?.response?.status === 404
      ) {
        toast.error(err?.response?.data?.message);
      } else {
        return "Internal Server Error.";
      }
    }
  }

  async function getLatestProducts() {
    try {
      const response = await axiosInstance.get("/products-latest");
      if (response.data && response.status === 200) {
        product.latestProduct = response.data;
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else if (
        err?.response?.status === 400 ||
        err?.response?.status === 404
      ) {
        toast.error(err?.response?.data?.message);
      } else {
        return "Internal Server Error.";
      }
    }
  }

  async function getProductReviews(productId) {
    try {
      const response = await axiosInstance.get(`/product/${productId}/ratings`);
      if (response.data && response.status === 200) {
        product.productRatings = response.data;
        console.log(response.data);
      }
    } catch (err) {
      if (!err.response?.status) {
        return err?.message;
      } else if (err.response?.status === 400) {
        toast.error(err.response?.data?.message);
      } else {
        return "Internal Server Error: " + err.response;
      }
    }
  }

  async function filterByPricesAndCategories(data, router) {
    try {
      product.isLoading = true;
      const response = await axiosInstance.post(
        "/product/filter-by-price-and-category",
        data
      );

      if (response.data && response.status === 200) {
        product.isLoading = false;
        product.filteredProduct = response.data;
        console.log(response.data);
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        if (err.response?.data?.price_range)
          toast.error(err.response?.data?.price_range[0]);
        if (err.response?.data?.categories)
          toast.error(err.response?.data?.categories[0]);
      } else if (err?.response?.status === 404) {
        toast.error(err?.response?.data?.message);
      } else {
        return "Internal Server Error";
      }
    }
  }

  async function searchProduct(search) {
    try {
      product.isLoading = true;
      const response = await axiosInstance.post("/product/search-all", search);
      if (response.data && response.status === 200) {
        product.isLoading = false;
        product.filteredProduct = response.data;
        console.log(response.data);
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        toast.error(err?.response?.data.search[0]);
      } else if (err?.response?.status === 404) {
        product.filteredProduct = [];
      } else {
        return "Internal Server Error";
      }
    }
  }

  return {
    product,
    getAllProduct,
    getProductById,
    getLatestProducts,
    getProductReviews,
    filterByPricesAndCategories,
    searchProduct,
  };
});
