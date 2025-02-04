import { defineStore } from "pinia";
import Cookies from "js-cookie";
import { useToast } from "vue-toastification";

import axiosPrivate from "@/api/axiosPrivate";
import { reactive } from "vue";

export const productStore = defineStore("product", () => {
  const toast = useToast();

  const products = reactive({
    vendorProducts: [],
    singleProduct: {},
    percentageOfProduct: [],
    totalProductAverage: 0,
    isLoading: false,
  });

  async function getVendorProducts(router) {
    try {
      products.isLoading = true;
      const response = await axiosPrivate.get("/vendor/products");
      if (response.data && response.status === 200) {
        products.isLoading = false;
        products.vendorProducts.splice(
          0,
          products.vendorProducts.length,
          ...response.data
        );
        // console.log(response.data);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        toast.error(err.response.data?.message);
        router.push({ name: "vendorLogin" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Inter Server Error: " + err.message;
      }
    }
  }

  async function getVendorProductById(productId) {
    try {
      const response = await axiosPrivate.get(`/vendor/product/${productId}`);
      if (response.data && response.status === 200) {
        Object.assign(products.singleProduct, response.data);
        console.log(response.data);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        if (err?.response?.status === 401) {
          router.push({ name: "vendorLogin" });
          return;
        }
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 404) {
        toast.error(err.response.data?.message);
      } else {
        return "Internal Server Error " + err.message;
      }
    }
  }

  async function averageProductsRating(router) {
    try {
      const response = await axiosPrivate.get("/vendor/product/average/rating");
      if (response.data && response.status === 200) {
        products.totalProductAverage = response.data;
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        if (err?.response?.status === 401) {
          router.push({ name: "vendorLogin" });
          return;
        }
      }

      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error " + err.message;
      }
    }
  }

  async function editProduct(data, productImages, productId, router) {
    try {
      products.isLoading = true;
      const { title, price, category, description } = data;
      const formData = new FormData();
      formData.append("title", title);
      formData.append("price", price);
      formData.append("category", category);
      formData.append("description", description);

      productImages.forEach((file, index) => {
        formData.append(`images[${index}]`, file);
      });

      // console.log(formData);

      const saveProduct = await axiosPrivate.post(
        `/vendor/updateproduct/${productId}`,
        formData,
        { headers: { "Content-Type": "multipart/form-data" } }
      );

      if (saveProduct.status === 201) {
        products.isLoading = false;
        toast.success("Product Updated successfully");
        router.push({ name: "dashboard" });
      }
    } catch (err) {
      // console.log(err);
      if (err?.response?.status === 401) {
        if (err?.response?.status === 401) {
          router.push({ name: "vendorLogin" });
          return;
        }
      }

      if (!err.response?.status) {
        products.isLoading = false;
        toast.info(err.message);
      } else if (err?.response?.status === 400) {
        products.isLoading = false;
        if (err.response?.data?.title)
          toast.error(err.response?.data?.title[0]);
        if (err.response?.data?.price)
          toast.error(err.response?.data?.price[0]);
        if (err.response?.data?.category)
          toast.error(err.response?.data?.category[0]);
        if (err.response?.data?.description)
          toast.error(err.response?.data?.description[0]);
        if (err.response?.data?.images) toast.error("fdfhdjjh");
      } else if (err?.response?.status === 404) {
        products.isLoading = false;
        toast.error(err.response?.data?.message);
      } else {
        products.isLoading = false;
        toast.error("Internal server error");
      }
    }
  }

  async function deleteProduct(productId, router) {
    try {
      const response = await axiosPrivate.delete(
        `/vendor/deleteproduct/${productId}`
      );
      if (response.data && response.status === 200) {
        products.vendorProducts = products.vendorProducts.filter(
          (product) => product.id !== productId
        );
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

  async function freezeProduct(productId, router) {
    try {
      const response = await axiosPrivate.post(
        `/vendor/freezeproduct/${productId}`
      );
      if (response.data && response.status === 201) {
        products.vendorProducts = products.vendorProducts.map((product) => {
          if (product.id === productId) {
            product.frozen = !product.frozen;
          }

          return product;
        });
        toast.success(response.data?.message);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        toast.error(err.response.data?.message);
        router.push({ name: "vendorLogin" });
        return;
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

  async function todaysProductPercentage() {
    try {
      const response = await axiosPrivate.get("/vendor/product-today");
      if (response.data && response.status === 200) {
        products.percentageOfProduct = response.data;
        // console.log(response.data, "0000000000");
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error: " + err.message;
      }
    }
  }

  async function monthsProductPercentage() {
    try {
      const response = await axiosPrivate.get("/vendor/product-month");
      if (response.data && response.status === 200) {
        products.percentageOfProduct = response.data;
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error: " + err.message;
      }
    }
  }

  async function yearsProductPercentage() {
    try {
      const response = await axiosPrivate.get("/vendor/product-year");
      if (response.data && response.status === 200) {
        products.percentageOfProduct = response.data;
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error: " + err.message;
      }
    }
  }

  return {
    products,
    getVendorProducts,
    getVendorProductById,
    averageProductsRating,
    editProduct,
    deleteProduct,
    freezeProduct,
    todaysProductPercentage,
    monthsProductPercentage,
    yearsProductPercentage,
  };
});
