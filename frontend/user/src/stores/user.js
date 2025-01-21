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

  async function logOut(router) {
    try {
      const response = await axiosPrivate.post("/user/logout");

      if (response.data && response.status === 200) {
        user.isAuthenticated = false;
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

  async function getWishlist(router) {
    try {
      const response = await axiosPrivate.get("/user/product-wishlist");
      if (response.data && response.status === 200) {
        user.isLoading = false;
        user.wishList = response.data;
        // console.log(response.data);
      }
      // return response.data;
    } catch (err) {
      if (err?.response?.status === 401) {
        user.isAuthenticated = false;
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
        user.isAuthenticated = false;
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
        user.wishList = user.wishList.filter(
          (product) => product.id !== productId
        );
        // alert("deleted");
        toast.success(response.data?.message, { timeout: 10 });
      }

      return;
    } catch (err) {
      if (err?.response?.status === 401) {
        user.isAuthenticated = false;
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
      if (err.response?.status === 401) {
        user.isAuthenticated = false;
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
        if (err?.response?.data?.message)
          toast.error(err?.response?.data?.message);
        if (err.response?.data?.rating)
          toast.error(err.response?.data?.rating[0]);
        if (err.response?.data?.comment)
          toast.error(err.response?.data?.comment[0]);
      } else if (err.response?.status === 404) {
        toast.error(err.response?.data?.message);
      } else {
        return "Interal Server Error: " + err.response;
      }
    }
  }

  async function requestCallback(productId) {
    try {
      const response = await axiosPrivate.post(
        `/user/product/${productId}/request-callback`
      );
      if (
        response.data &&
        (response.status === 201 || response.status === 200)
      ) {
        if (response.data?.message) toast.success(response.data?.message);
      }
    } catch (err) {
      if (err.response?.status === 401) {
        user.isAuthenticated = false;
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

  async function reportProduct(productId) {
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
        user.isAuthenticated = false;
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

  return {
    user,
    loginFn,
    verifyTokenFn,
    logOut,
    getWishlist,
    addProductToWishlist,
    deleteProductFromWishlist,
    rateProduct,
    requestCallback,
    reportProduct,
  };
});
