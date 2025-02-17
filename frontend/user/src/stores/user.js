import { reactive } from "vue";
import { defineStore } from "pinia";
import { useRouter } from "vue-router";

import Cookies from "js-cookie";
import axiosInstance from "@/api/axiosInstance";
import { useToast } from "vue-toastification";

import CryptoJS from "crypto-js";
import axiosPrivate from "@/api/axiosPrivate";

export const userStore = defineStore("user", () => {
  /*
 states
*/

  // const router = useRouter();
  const toast = useToast();

  const store = reactive({
    details: {
      info: null,
      servicenumber: Cookies.get("service_id") || null,
      telephone: null,
    },
    wishList: [],
    isAuthenticated: Cookies.get("token_u") || false,
    isLoading: true,
    isMailSent: false,
    all_product: [],
    singleProduct: {},
    latestProduct: [],
    similarProducts: [],
    filteredProduct: [],
    productRatings: [],
  });

  /*
 acions
*/

  async function loginFn(credentials, router) {
    try {
      const response = await axiosInstance.post("/user/login", credentials);

      if (response.data && response.status === 200) {
        const ciphertext = CryptoJS.AES.encrypt(
          JSON.stringify(response.data?.telephone),
          "09690"
        ).toString();

        Cookies.set("service_p", ciphertext, {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });

        store.details.telephone = Cookies.get("telephone");

        toast.success(response.data.message);
        router.push({ name: "verify" });
      }

      return;
    } catch (err) {
      console.log(err);
      if (!err?.response?.status) {
        toast.error(err?.message);
      } else if (err?.response?.status === 400) {
        if (err.response?.data?.servicenumber)
          toast.error(err.response?.data?.servicenumber[0]);
        if (err.response?.data?.telephone)
          toast.error(err.response?.data?.telephone[0]);
      } else if (err?.response?.status === 401) {
        toast.error(err?.response?.data?.message);
      } else {
        toast.error("Internal Server Error");
      }
    }
  }

  async function verifyTokenFn(token, router) {
    try {
      const response = await axiosInstance.post("/user/login/verify", token);
      if (response.data && response.status === 200) {
        const { accessToken } = response?.data;
        Cookies.set("token_u", accessToken, {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });

        store.isAuthenticated = Cookies.get("token_u");
        toast.success(response.data.message);
        router.push("/");
      }

      return;
    } catch (err) {
      if (!err?.response?.status) {
        toast.error(err?.message);
      } else if (err?.response?.status === 400) {
        toast.error(err?.response?.data?.token[0]);
      } else if (err?.response?.status === 401) {
        toast.error(err?.response?.data?.message);
      } else {
        toast.error("Internal Error");
      }
    }
  }

  async function logOut(router) {
    try {
      const response = await axiosPrivate.post("/user/logout");

      if (response.data && response.status === 200) {
        store.isAuthenticated = false;
        Cookies.remove("token_u", {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });
        toast.success(response?.data?.message);
        router.push({ name: "Login" });
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        router.push({ name: "Login" });
        return;
      }
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error";
      }
    }
  }

  async function currentUser(router) {
    try {
      const response = await axiosPrivate.get("/user/details");
      if (response.data && response.status === 200) {
        store.details.info = response.data;
        console.log(response.data);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        if (err?.response?.status === 401) {
          router.push({ name: "Login" });
          return;
        }
      }
      if (!err?.response?.status) {
        return err.message;
      } else {
        return "Internal Server Error " + err.response;
      }
    }
  }

  async function getWishlist(router) {
    try {
      const response = await axiosPrivate.get("/user/product-wishlist");
      if (response.data && response.status === 200) {
        // store.isLoading = false;
        store.wishList = response.data;

        console.log(response.data, "JJJJ");
      }
      // return response.data;
    } catch (err) {
      if (err?.response?.status === 401) {
        store.isAuthenticated = false;
        router.push({ name: "Login" });
        return;
      }
      if (!err?.response?.status) {
        return err?.message;
      } else {
        toast.error("Internal Sever Error");
      }
    }
  }

  async function getAllProduct() {
    try {
      const response = await axiosInstance.get("/products-all");

      if (response.data && response.status === 200) {
        store.all_product = response.data;
        store.isLoading = false;
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
      store.isLoading = true;
      const response = await axiosInstance.get(`/product/single/${productId}`);
      if (response.data && response.status === 200) {
        store.isLoading = false;
        store.singleProduct = response.data;
        console.log(response.data);
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
        store.latestProduct = response.data;
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

  async function getSimilarProducts(data) {
    try {
      const response = await axiosInstance.post("/products-similar", data);
      if (response.data && response.status === 200) {
        store.similarProducts = response.data;
        console.log(response.data, "similar");
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error.";
      }
    }
  }

  async function getProductReviews(productId) {
    try {
      const response = await axiosPrivate.get(`/product/${productId}/ratings`);
      if (response.data && response.status === 200) {
        store.productRatings = response.data;
        console.log(response.data, "reviews");
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
      store.isLoading = true;
      const response = await axiosInstance.post(
        "/product/filter-by-price-and-category",
        data
      );

      if (response.data && response.status === 200) {
        store.isLoading = false;
        store.filteredProduct = response.data;
        console.log(response.data, "filter");
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
      store.isLoading = true;
      const response = await axiosInstance.post("/product/search-all", search);
      if (response.data && response.status === 200) {
        store.isLoading = false;
        store.filteredProduct = response.data;
        console.log(response.data);
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        toast.error(err?.response?.data.search[0]);
      } else if (err?.response?.status === 404) {
        store.filteredProduct = [];
      } else {
        return "Internal Server Error";
      }
    }
  }

  async function addProductToWishlist(productId, router) {
    try {
      const response = await axiosPrivate.post(
        `/user/product/${productId}/add-wishlist`
      );

      if (response.data && response.status === 201) {
        toast.success(response.data, { timeout: 10 });
      }

      return;
    } catch (err) {
      if (err?.response?.status === 401) {
        store.isAuthenticated = false;
        Cookies.remove("token_u", {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });
        router.push({ name: "Login" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        toast.error(err?.response?.data.message, { timeout: 10 });
      } else if (err?.response?.status === 404) {
        toast.error(err?.response?.data.message, { timeout: 10 });
      } else {
        return "Internal Server Error";
      }
    }
  }

  async function deleteProductFromWishlist(productId, router) {
    try {
      const response = await axiosPrivate.delete(
        `/user/product/${productId}/remove-wishlist`
      );

      if (response.data && response.status === 200) {
        toast.success(response.data?.message, { timeout: 100 });
      }

      return;
    } catch (err) {
      if (err?.response?.status === 401) {
        store.isAuthenticated = false;
        Cookies.remove("token_u", {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });
        router.push({ name: "Login" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        return err?.message;
      } else if (err?.response?.status === 404) {
        toast.error(err?.response?.data?.message);
      } else {
        return "Internal Error";
      }
    }
  }

  async function rateProduct(productId, data, router) {
    try {
      const response = await axiosPrivate.post(
        `/user/product/${productId}/rate`,
        data
      );

      if (response.data && response.status === 201) {
        toast.success(response?.data?.message);
      }
    } catch (err) {
      console.log(err?.response?.data?.message);
      if (err.response?.status === 401) {
        store.isAuthenticated = false;
        Cookies.remove("token_u", {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });
        router.push({ name: "Login" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        console.log(err?.response.data);
        if (err?.response?.data?.message)
          toast.error(err?.response?.data?.message);
        if (err.response?.data?.rating)
          toast.error(err.response?.data?.rating[0]);
        if (err.response?.data?.comment)
          toast.error(err.response?.data?.comment[0]);
        if (err.response?.data?.shopvendor_id)
          toast.error(err.response?.data?.shopvendor_id[0]);
      } else if (err.response?.status === 404) {
        toast.error(err.response?.data?.message);
      } else {
        return "Interal Server Error: " + err.response;
      }
    }
  }

  async function requestCallback(productId, data, router) {
    try {
      const response = await axiosPrivate.post(
        `/user/product/${productId}/request-callback`,
        data
      );
      if (
        response.data &&
        (response.status === 201 || response.status === 200)
      ) {
        if (response.data?.message) toast.success(response.data?.message);
      }
    } catch (err) {
      if (err.response?.status === 401) {
        store.isAuthenticated = false;
        Cookies.remove("token_u", {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });
        router.push({ name: "Login" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err.response?.status === 400 || err.response?.status === 404) {
        toast.success(err.response.data?.message);
      } else {
        return "Internal Server Error " + err.response;
      }
    }
  }

  async function reportProduct(productId, router) {
    try {
      const response = await axiosPrivate.post(
        `/user/product/${productId}/report`
      );
      if (
        response.data &&
        (response.status === 201 || response.status === 200)
      ) {
        if (response.data?.message) toast.success(response.data?.message);
      }
    } catch (err) {
      if (err.response?.status === 401) {
        store.isAuthenticated = false;
        Cookies.remove("token_u", {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });
        router.push({ name: "Login" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err.response?.status === 400 || err.response?.status === 404) {
        toast.success(err.response.data?.message);
      } else {
        return "Internal Server Error " + err.response;
      }
    }
  }

  async function getProductReviews(productId) {
    try {
      const response = await axiosPrivate.get(`/product/${productId}/ratings`);
      if (response.data && response.status === 200) {
        store.productRatings = response.data;
        console.log(response.data, "reviews");
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

  async function contactAdmin(data, router) {
    try {
      store.isMailSent = true;
      const response = await axiosPrivate.post("/user/contact-admin", data);
      if (response.data && response.status === 200) {
        store.isMailSent = false;
        toast.success(response.data);
      }
    } catch (err) {
      if (err.response?.status === 401) {
        store.isAuthenticated = false;
        Cookies.remove("token_u", {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });
        router.push({ name: "Login" });
        return;
      }
      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        store.isMailSent = false;
        if (err?.response?.data?.subject)
          toast.error(err?.response?.data?.subject[0]);
        if (err?.response?.data?.message)
          toast.error(err?.response?.data?.message[0]);
      } else {
        return "Internal Server Error: " + err.response;
      }
    }
  }

  return {
    store,
    loginFn,
    verifyTokenFn,
    logOut,
    currentUser,
    getWishlist,
    getAllProduct,
    getProductById,
    getLatestProducts,
    getSimilarProducts,
    filterByPricesAndCategories,
    searchProduct,
    getProductReviews,
    addProductToWishlist,
    deleteProductFromWishlist,
    rateProduct,
    requestCallback,
    reportProduct,
    contactAdmin,
  };
});
