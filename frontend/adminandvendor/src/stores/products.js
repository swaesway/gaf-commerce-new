import { defineStore } from "pinia";
import Cookies from "js-cookie";
import { useToast } from "vue-toastification";

import axiosPrivate from "@/api/axiosPrivate";
import { reactive } from "vue";

export const productStore = defineStore("product", () => {
  const toast = useToast();

  const products = reactive({
    vendorProducts: [],
    isLoading: false,
  });

  async function getVendorProducts(router) {
    try {
      products.isLoading = true;
      const response = await axiosPrivate.get("/vendor/products");
      if (response.data && response.status === 200) {
        products.isLoading = false;
        products.vendorProducts = response.data;
        console.log(response.data);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        toast.error(err.response.data?.message);
        router.push({ name: "vendorLogin" });
      }
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Inter Server Error: " + err.message;
      }
    }
  }

  async function deleteProduct(productId, router) {
    try {
      const response = await axiosPrivate.delete(
        `/vendor/deleteproduct/${productId}`
      );
      if (response.data && response.status === 200) {
        toast.success(response.data);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        toast.error(err.response.data?.message);
        router.push({ name: "vendorLogin" });
      }
      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 404) {
        toast.error(err.response.data?.message);
      } else {
        return "Internal Server Error: " + err.message;
      }
    }
  }

  return {
    products,
    getVendorProducts,
    deleteProduct,
  };
});
