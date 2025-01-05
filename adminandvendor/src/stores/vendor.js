import { onBeforeMount, reactive } from "vue";
import { useRouter } from "vue-router";
import { defineStore } from "pinia";

import { useToast } from "vue-toastification";

import axiosInstance from "@/api/axiosInstance";

import Cookies from "js-cookie";
import axiosPrivate from "@/api/axiosPrivate";

export const useVendorStore = defineStore("vendor", () => {
  const router = useRouter();
  const toast = useToast();

  /* 
   states
  */

  const vendor = reactive({
    details: {
      id: null,
      email: "",
      shopname: "",
    },
    isAuthenticated: Cookies.get("token") || false,
    accessToken: null,
  });

  /* 
   actions
  */

  async function checkAuthStatus() {
    try {
      const response = await axiosInstance.get("/vendor/status");
      vendor.isAuthenticated = response.data || false;
      console.log(response.data);
    } catch (err) {
      vendor.isAuthenticated = false;
      console.log(err);
      if (err.response?.status === 401) {
        console.log(err.response);
        toast.error("Unauthorized access. Please log in.");
      } else {
        toast.error("Internal Server Error.");
      }
    }
  }

  async function registerFn(credentials) {
    try {
      const formData = new FormData();
      const {
        shopname,
        email,
        telephone,
        location,
        region,
        password,
        password_confirmation,
        proof_of_business,
      } = credentials;

      formData.append("shopname", shopname);
      formData.append("email", email);
      formData.append("telephone", telephone);
      formData.append("location", location);
      formData.append("region", region);
      formData.append("pob", proof_of_business);
      formData.append("password", password);
      formData.append("password_confirmation", password_confirmation);

      const response = await axiosInstance.post("/vendor/register", formData);

      const message = response?.data?.message;

      if (message && response.status === 201) {
        router.push({ name: "vendorLogin" });
        toast.success(message);
      }

      return;
    } catch (err) {
      if (!err.response.status) {
        toast.info(err.message);
      } else if (err?.response?.status === 400) {
        if (err.response?.data?.shopname)
          toast.error(err.response?.data?.shopname[0]);
        if (err.response?.data?.email)
          toast.error(err.response?.data?.email[0]);
        if (err.response?.data?.telephone)
          toast.error(err.response?.data?.telephone[0]);
        if (err.response?.data?.location)
          toast.error(err.response?.data?.location[0]);
        if (err.response?.data?.region)
          toast.error(err.response?.data?.region[0]);
        if (err.response?.data?.password)
          toast.error(err.response?.data?.password[0]);
        if (err.response?.data?.password)
          toast.error(err.response?.data?.password_confirmation[0]);
        if (err.response?.data?.pob) toast.error(err.response?.data?.pob[0]);
      } else {
        toast.error("Internal server error");
      }
    }
  }

  async function loginFn(credentials) {
    try {
      const response = await axiosInstance.post("/vendor/login", credentials);

      const message = response?.data?.message;
      console.log(response.data);

      if (response.status === 200) {
        Cookies.set("token", response?.data?.access_token, {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });

        vendor.isAuthenticated = Cookies.get("token");
        toast.success(message);
        router.push({ name: "dashboard" });
      }

      return;
    } catch (err) {
      console.log(err);
      if (!err.response.status) {
        toast.info(err.message);
      } else if (err?.response?.status === 400) {
        if (err.response?.data?.email)
          toast.error(err.response?.data?.email[0]);
        if (err.response?.data?.password)
          toast.error(err.response?.data?.password[0]);
      } else if (err?.response?.status === 401) {
        toast.error(err.response?.data?.message);
      } else if (err?.response?.status === 403) {
        toast.error(err.response?.data?.message);
      } else {
        toast.error("Internal server error");
      }
    }
  }

  async function getVendorDetails(params) {
    try {
      const vendorDetais = await axiosPrivate.get("/vendor/owner/dashboard");

      if (vendorDetais.data && vendorDetais.status === 200) {
        const { id, email, shopname } = vendorDetais.data;
        vendor.details.id = id;
        vendor.details.email = email;
        vendor.details.shopname = shopname;
      }

      return;
    } catch (err) {
      return err;
    }
  }

  async function addProduct(data, productImages) {
    try {
      const { title, price, category, description } = data;

      const formData = new FormData();
      formData.append("title", title);
      formData.append("price", price);
      formData.append("category", category);
      formData.append("description", description);

      productImages.forEach((file, index) => {
        formData.append(`images[${index}]`, file);
      });

      const saveProduct = await axiosPrivate.post(
        "/vendor/addproduct",
        formData
      );

      if (saveProduct.status === 201) {
        toast.success("Product Added successfully");
        router.push({ name: "dashboard" });
      }

      return;
    } catch (err) {
      if (!err.response?.status) {
        toast.info(err.message);
      } else if (err?.response?.status === 400) {
        if (err.response?.data?.title)
          toast.error(err.response?.data?.title[0]);
        if (err.response?.data?.price)
          toast.error(err.response?.data?.price[0]);
        if (err.response?.data?.category)
          toast.error(err.response?.data?.category[0]);
        if (err.response?.data?.description)
          toast.error(err.response?.data?.description[0]);
        if (err.response?.data?.images)
          toast.error(err.response?.data?.images[0]);
      } else if (err?.response?.status === 404) {
        toast.error(err.response?.data?.message);
      } else {
        toast.error("Internal server error");
      }
    }
  }

  return {
    vendor,
    registerFn,
    loginFn,
    getVendorDetails,
    addProduct,
    checkAuthStatus,
  };
});
