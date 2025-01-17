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

  const user = reactive({
    details: {
      servicenumber: Cookies.get("service_id") || null,
      telephone: null,
    },
    wishList: [],
    isAuthenticated: Cookies.get("token_u") || false,
    isLoading: true,
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

        user.details.telephone = Cookies.get("telephone");

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

        user.isAuthenticated = Cookies.get("token_u");
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

  async function getWishlist(router) {
    try {
      const response = await axiosPrivate.get("/user/product-wishlist");
      if (response.data && response.status === 200) {
        user.isLoading = false;
        user.wishList = response.data;
        console.log(response.data);
      }
      // return response.data;
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        toast.error("Internal Sever Error");
      }
    }
  }

  async function addProductToWishlist(productId, router) {
    try {
      const response = await axiosPrivate.post(
        `/user/product/${productId}/add-wishlist`
      );

      if (response.data && response.status === 201) {
        toast.success(response.data);
      }

      return;
    } catch (err) {
      if (err?.response?.status === 401) {
        router.push({ name: "Login" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err?.response?.status === 400) {
        toast.error(err?.response?.data.message);
      } else if (err?.response?.status === 404) {
        toast.error(err?.response?.data.message);
      } else {
        toast.error("Internal Server Error");
      }
    }
  }

  return {
    user,
    loginFn,
    verifyTokenFn,
    getWishlist,
    addProductToWishlist,
  };
});
