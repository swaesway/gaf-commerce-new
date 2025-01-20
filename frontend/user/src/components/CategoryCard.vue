<script>
import { RouterLink } from 'vue-router';

export default {
  name: "CategoryCard",
  props: {
    name: {
      type: String,
      required: true,
    },
    image: {
      type: String,
      required: true,
    },
  },
};
</script>

<template>
  <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
    <RouterLink class="text-decoration-none" :to="'/Shop?categories=' + name">
      <div class="cat-item d-flex align-items-center mb-4">
        <div class="overflow-hidden" style="width: 100px; height: 100px">
          <img class="img-fluid" :src="image" :alt="name" />
        </div>
        <div class="flex-fill pl-3">
          <h6>{{ name }}</h6>
          <small class="text-body">100 Products</small>
        </div>
      </div>
    </RouterLink>
  </div>
</template>
