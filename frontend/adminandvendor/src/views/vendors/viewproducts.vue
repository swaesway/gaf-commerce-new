<template>
  <div class="container-fluid py-4">
    <!-- Header Section with enhanced search -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">View Products</h2>
      <div class="d-flex gap-3">
        <div class="d-flex gap-2">
          <input
            type="search"
            class="form-control"
            placeholder="Search Product"
            v-model="nameQuery"
          />
          <select
            class="form-select"
            v-model="categoryQuery"
            style="min-width: 150px"
          >
            <option value="">All Categories</option>
            <option
              v-for="category in uniqueCategories"
              :key="category"
              :value="category"
            >
              {{ category }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Rest of your table code stays the same until tbody -->
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in filteredProducts" :key="product.id">
                <!-- Rest of your existing table row code -->
                <td>{{ product.id }}</td>
                <td>{{ product.name }}</td>
                <td>{{ product.category }}</td>
                <td>
                  <span
                    :class="[
                      'badge',
                      product.status === 'active' ? 'bg-success' : 'bg-danger',
                    ]"
                  >
                    {{ product.status }}
                  </span>
                </td>
                <td>
                  <div class="dropdown">
                    <button
                      class="btn btn-secondary btn-sm dropdown-toggle"
                      type="button"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      Actions
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="updateProduct(product)"
                        >
                          <i class="bi bi-pencil me-2"></i>Update
                        </a>
                      </li>
                      <li>
                        <a
                          class="dropdown-item"
                          href="#"
                          @click.prevent="toggleFreeze(product)"
                        >
                          <i
                            class="bi"
                            :class="[
                              product.status === 'active'
                                ? 'bi-snow'
                                : 'bi-snow-fill',
                            ]"
                          ></i>
                          {{
                            product.status === "active" ? "Freeze" : "Unfreeze"
                          }}
                        </a>
                      </li>
                      <li><hr class="dropdown-divider" /></li>
                      <li>
                        <a
                          class="dropdown-item text-danger"
                          href="#"
                          @click.prevent="deleteProduct(product)"
                        >
                          <i class="bi bi-trash me-2"></i>Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Updated no results message -->
        <div v-if="filteredProducts.length === 0" class="text-center py-4">
          <p class="text-muted">
            No products found matching your search criteria
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const products = ref([
  {
    id: 1,
    name: "Wireless Earbuds",
    category: "Electronics",
    status: "active",
  },
  {
    id: 2,
    name: "Bluetooth Speaker",
    category: "Electronics",
    status: "active",
  },
  {
    id: 3,
    name: "Smartphone Case",
    category: "Clothing",
    status: "frozen",
  },
  {
    id: 4,
    name: "Running Shoes",
    category: "Footwear",
    status: "active",
  },
  {
    id: 5,
    name: "Leather Jacket",
    category: "Clothing",
    status: "active",
  },
  {
    id: 6,
    name: "Digital Watch",
    category: "Electronics",
    status: "active",
  },
  {
    id: 7,
    name: "Cosmetic Kit",
    category: "Cosmetics",
    status: "frozen",
  },
  {
    id: 8,
    name: "Sports Cap",
    category: "Headgear",
    status: "active",
  },
  {
    id: 9,
    name: "Hardcover Notebook",
    category: "Books & Stationary",
    status: "active",
  },
  {
    id: 10,
    name: "Organic Tea Pack",
    category: "Food & Beverages",
    status: "frozen",
  },
  {
    id: 11,
    name: "Noise-Canceling Headphones",
    category: "Electronics",
    status: "active",
  },
  {
    id: 12,
    name: "Silk Scarf",
    category: "Clothing",
    status: "frozen",
  },
  {
    id: 13,
    name: "Sneakers",
    category: "Footwear",
    status: "active",
  },
  {
    id: 14,
    name: "Lipstick Set",
    category: "Cosmetics",
    status: "active",
  },
  {
    id: 15,
    name: "Winter Hat",
    category: "Headgear",
    status: "active",
  },
  {
    id: 16,
    name: "Self-Help Book",
    category: "Books & Stationary",
    status: "frozen",
  },
  {
    id: 17,
    name: "Pack of Coffee Beans",
    category: "Food & Beverages",
    status: "active",
  },
  {
    id: 18,
    name: "LED Desk Lamp",
    category: "Electronics",
    status: "active",
  },
  {
    id: 19,
    name: "Denim Jeans",
    category: "Clothing",
    status: "frozen",
  },
  {
    id: 20,
    name: "Waterproof Boots",
    category: "Footwear",
    status: "active",
  },
]);

// Separate queries for name and category
const nameQuery = ref("");
const categoryQuery = ref("");

// Compute unique categories for the dropdown
const uniqueCategories = computed(() => {
  const categories = new Set(products.value.map((product) => product.category));
  return Array.from(categories).sort();
});

// Updated filtered products computed property
const filteredProducts = computed(() => {
  let filtered = products.value;

  // Filter by name if there's a name query
  const name = nameQuery.value.toLowerCase().trim();
  if (name) {
    filtered = filtered.filter((product) =>
      product.name.toLowerCase().includes(name)
    );
  }

  // Filter by category if a category is selected
  if (categoryQuery.value) {
    filtered = filtered.filter(
      (product) => product.category === categoryQuery.value
    );
  }

  return filtered;
});

// Your existing action handlers stay the same
const updateProduct = (product) => {
  console.log("Update product:", product);
};

const toggleFreeze = (product) => {
  product.status = product.status === "active" ? "frozen" : "active";
  console.log(
    `${product.status === "active" ? "Unfroze" : "Froze"} product:`,
    product
  );
};

const deleteProduct = (product) => {
  if (confirm(`Are you sure you want to delete ${product.name}?`)) {
    console.log("Delete product:", product);
  }
};
</script>

<style scoped>
.dropdown-item i {
  width: 1rem;
  margin-right: 0.5rem;
}
</style>
