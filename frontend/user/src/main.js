import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import { createPinia } from "pinia";

const app = createApp(App);
const pinia = createPinia();

// @desc allow route access in pinia
pinia.use(() => {
  router;
});

app.use(Toast);
app.use(router);
app.use(pinia);

app.mount("#app");
