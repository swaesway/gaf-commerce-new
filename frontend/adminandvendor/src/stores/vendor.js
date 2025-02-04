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
    isLoading: false,
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
      vendor.isLoading = true;
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
        vendor.isLoading = false;
        router.push({ name: "vendorLogin" });
        toast.success(message);
      }

      return;
    } catch (err) {
      if (!err.response.status) {
        vendor.isLoading = false;
        toast.info(err.message);
      } else if (err?.response?.status === 400) {
        vendor.isLoading = false;
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
        vendor.isLoading = false;
        toast.error("Internal server error");
      }
    }
  }

  async function loginFn(credentials) {
    try {
      vendor.isLoading = true;
      const response = await axiosInstance.post("/vendor/login", credentials);

      const message = response?.data?.message;
      console.log(response.data);

      if (response.status === 200) {
        vendor.isLoading = false;
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
        vendor.isLoading = false;
        toast.info(err.message);
      } else if (err?.response?.status === 400) {
        vendor.isLoading = false;
        if (err.response?.data?.email)
          toast.error(err.response?.data?.email[0]);
        if (err.response?.data?.password)
          toast.error(err.response?.data?.password[0]);
      } else if (err?.response?.status === 401) {
        vendor.isLoading = false;
        toast.error(err.response?.data?.message);
      } else if (err?.response?.status === 403) {
        vendor.isLoading = false;
        toast.error(err.response?.data?.message);
      } else {
        vendor.isLoading = false;
        toast.error("Internal server error");
      }
    }
  }

  async function logOutFn(router) {
    try {
      const response = await axiosPrivate.post("/vendor/logout");
      if (response.data && response.status === 200) {
        vendor.isAuthenticated = false;
        Cookies.remove("token", response?.data?.access_token, {
          expires: 2 * 60 * 1000,
          path: "/",
          secure: true,
          sameSite: "Strict",
        });

        toast.success(response.data);
        router.push({ name: "vendorLogin" });
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
      } else {
        return "Internal Server Error " + err.message;
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
      vendor.isLoading = true;
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
        // console.log(saveProduct);
        vendor.isLoading = false;
        toast.success("Product Added successfully");
        router.push({ name: "dashboard" });
      }

      return;
    } catch (err) {
      console.log(err);
      if (!err.response?.status) {
        vendor.isLoading = false;
        toast.info(err.message);
      } else if (err?.response?.status === 400) {
        vendor.isLoading = false;
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
        vendor.isLoadig = false;
        toast.error(err.response?.data?.message);
      } else {
        vendor.isLoading = false;
        toast.error("Internal server error");
      }
    }
  }

  return {
    vendor,
    registerFn,
    loginFn,
    logOutFn,
    getVendorDetails,
    addProduct,
    checkAuthStatus,
  };
});
