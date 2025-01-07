import axios from "axios";
import Cookies from "js-cookie";

import { useToast } from "vue-toastification";

import { useVendorStore } from "@/stores/vendor";

const toast = useToast();

const axiosPrivate = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
});

axiosPrivate.interceptors.request.use(
  async (config) => {
    const token = Cookies.get("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (err) => {
    return Promise.reject(err);
  }
);

axiosPrivate.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      Cookies.remove("token", {
        expires: 2 * 60 * 1000,
        path: "/",
        secure: true,
        sameSite: "Strict",
      });
      toast.error("Unauthorized, please try to login");
    }

    return Promise.reject(error);
  }
);

export default axiosPrivate;
