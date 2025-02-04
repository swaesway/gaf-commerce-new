import { defineStore } from "pinia";
import Cookies from "js-cookie";
import { useToast } from "vue-toastification";

import axiosPrivate from "@/api/axiosPrivate";
import { reactive } from "vue";

export const callbackStore = defineStore("callbacks", () => {
  const toast = useToast();

  const callbacks = reactive({
    myCallbacks: [],
    singleCallback: [],
    isLoading: false,
    isCallbackLoading: false,
  });

  async function getVendorCallbacks() {
    try {
      callbacks.isCallbackLoading = true;
      const response = await axiosPrivate.get("/vendor/callbacks");
      if (response.data && response.status === 200) {
        callbacks.isCallbackLoading = false;
        callbacks.myCallbacks = response.data;
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error: " + err.message;
      }
    }
  }

  async function getCallbackById(callbackId) {
    try {
      callbacks.isCallbackLoading = true;
      const response = await axiosPrivate.get(
        `/vendor/callbacks/${callbackId}`
      );
      if (response.data && response.status === 200) {
        callbacks.isCallbackLoading = false;
        callbacks.myCallbacks = callbacks.myCallbacks.map((callback) => {
          if (callback.callback_id === +callbackId) {
            callback.callback_view = 1;
          }

          return callback;
        });
        callbacks.singleCallback = response.data;
      }
    } catch (err) {
      if (!err?.response?.status) {
        return err?.message;
      } else {
        return "Internal Server Error: " + err.message;
      }
    }
  }

  async function callbackStatus(callbackId, status, router) {
    try {
      const response = await axiosPrivate.post(
        `/vendor/callback/${callbackId}/status?status=${status}`
      );
      if (response.data && response.status === 200) {
        callbacks.singleCallback[0].callback_status = status;
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        vendor.isAuthenticated = false;
        Cookies.set("token", vendor.accessToken, {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });

        router.push({ name: "vendorLogin" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err.response.status === 400 || err.response.status === 404) {
        toast.error(err.response.data?.message);
      } else {
        return "Internal Server Error " + err.message;
      }
    }
  }

  async function hideCallback(callbackId, router) {
    try {
      const response = await axiosPrivate.post(
        `/vendor/callback/hide/${callbackId}`
      );
      if (response.data && response.status === 200) {
        callbacks.myCallbacks = callbacks.myCallbacks.filter(
          (callback) => callback.callback_id !== callbackId
        );
        router.push({ name: "dashboard" });
        // toast.success(response.data);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        vendor.isAuthenticated = false;
        Cookies.set("token", vendor.accessToken, {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });

        router.push({ name: "vendorLogin" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err.response.status === 400 || err.response.status === 404) {
        toast.error(err.response.data?.message);
      } else {
        return "Internal Server Error " + err.message;
      }
    }
  }

  async function viewCallback(callbackId, router) {
    try {
      const response = await axiosPrivate.post(
        `/vendor/callback/view/${callbackId}`
      );
      if (response.data && response.status === 200) {
        callbacks.myCallbacks = callbacks.myCallbacks.map((callback) => {
          if (callback.callback_id === callbackId) {
            callback.callback_view = 1;
          }

          return callback;
        });

        // toast.success(response.data);
      }
    } catch (err) {
      if (err?.response?.status === 401) {
        vendor.isAuthenticated = false;
        Cookies.set("token", vendor.accessToken, {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });

        router.push({ name: "vendorLogin" });
        return;
      }

      if (!err?.response?.status) {
        return err?.message;
      } else if (err.response.status === 400 || err.response.status === 404) {
        toast.error(err.response.data?.message);
      } else {
        return "Internal Server Error " + err.message;
      }
    }
  }

  return {
    callbacks,
    getVendorCallbacks,
    getCallbackById,
    callbackStatus,
    hideCallback,
    viewCallback,
  };
});
