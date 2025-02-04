<template>
  <div>
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/admin/">Dashboard</a></li>
        </ol>
      </nav>
    </div>

    <main class="main">
      <section class="section dashboard">
        <div class="row">
          <!-- Left side columns -->
          <div class="col-lg-15">
            <div class="row">
              <!-- Manage Vendors Card -->
              <div class="col-xxl-4 col-xl-4">
                <div class="card info-card customers-card">
                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown">
                      <i class="bi bi-three-dots"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>
                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Manage Vendors <span>| Overview</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-shop"></i>
                      </div>
                      <div class="ps-3">
                        <h6>50</h6>
                        <span class="text-success small pt-1 fw-bold">8%</span>
                        <span class="text-muted small pt-2 ps-1">increase</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Manage Vendors Card -->

              <!-- Manage Users Card -->
              <div class="col-xxl-4 col-md-6 col-xl-4">
                <div class="card info-card sales-card">
                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown">
                      <i class="bi bi-three-dots"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>
                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Manage Users <span>| Overview</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6>150</h6>
                        <span class="text-danger small pt-1 fw-bold">12%</span>
                        <span class="text-muted small pt-2 ps-1">decrease</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Manage Users Card -->

              <!-- Manage Reports Card -->
              <div class="col-xxl-4 col-md-6 col-xl-4">
                <div class="card info-card revenue-card">
                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown">
                      <i class="bi bi-three-dots"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>
                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">Manage Reports <span>| Overview</span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-file-earmark-text"></i>
                      </div>
                      <div class="ps-3">
                        <h6>45</h6>
                        <span class="text-success small pt-1 fw-bold">8%</span>
                        <span class="text-muted small pt-2 ps-1">increase</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Manage Reports Card -->
            </div>

            <!-- Vendors Table -->
            <section class="section">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Vendors</h5>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead class="table-light">
                            <tr>
                              <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Status</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(vendor, index) in vendors" :key="vendor.id">
                              <th scope="row">{{ index + 1 }}</th>
                              <td>{{ vendor.name }}</td>
                              <td>{{ vendor.email }}</td>
                              <td>
                                <span :class="`badge bg-${vendor.statusBadge}`">
                                  {{ vendor.statusText }}
                                </span>
                              </td>
                              <td>
                                <div class="dropdown">
                                  <button
                                    class="btn btn-secondary btn-sm dropdown-toggle compact-dropdown"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                  >
                                    Actions
                                  </button>
                                  <ul class="dropdown-menu compact-dropdown-menu">
                                    <li>
                                      <a
                                        class="dropdown-item"
                                        href="#"
                                        @click.prevent="viewVendor(vendor.id)"
                                      >
                                        <i class="bi bi-eye me-2"></i>View
                                      </a>
                                    </li>
                                    <li>
                                      <a
                                        class="dropdown-item"
                                        href="#"
                                        @click.prevent="toggleVendorStatus(vendor.id)"
                                      >
                                        <i
                                          class="bi"
                                          :class="[
                                            vendor.statusText === 'Active'
                                              ? 'bi-snow'
                                              : 'bi-snow-fill',
                                          ]"
                                        ></i>
                                        {{ vendor.statusText === "Active" ? "Block" : "Unblock" }}
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div v-if="vendors.length === 0" class="text-center py-4">
                        <p class="text-muted">No vendors found</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <!-- End Vendors Table -->

            <!-- Reports Table -->
            <section class="section">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Current Reports</h5>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead class="table-light">
                            <tr>
                              <th>#</th>
                              <th>Description</th>
                              <th>Reported User</th>
                              <th>Status</th>
                              <th>Time</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(report, index) in reports" :key="report.id">
                              <th scope="row">{{ index + 1 }}</th>
                              <td>{{ report.description }}</td>
                              <td>{{ report.reportedUser }}</td>
                              <td>
                                <span :class="`badge bg-${report.statusBadge}`">
                                  {{ report.statusText }}
                                </span>
                              </td>
                              <td>{{ report.timestamp }}</td>
                              <td>
                                <div class="dropdown">
                                  <button
                                    class="btn btn-secondary btn-sm dropdown-toggle compact-dropdown"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                  >
                                    Actions
                                  </button>
                                  <ul class="dropdown-menu compact-dropdown-menu">
                                    <li>
                                      <a
                                        class="dropdown-item"
                                        href="#"
                                        @click.prevent="viewReport(report.id)"
                                      >
                                        <i class="bi bi-eye me-2"></i>View
                                      </a>
                                    </li>
                                    <li>
                                      <a
                                        class="dropdown-item"
                                        href="#"
                                        @click.prevent="resolveReport(report.id)"
                                      >
                                        <i class="bi bi-check-circle me-2"></i>Resolve
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div v-if="reports.length === 0" class="text-center py-4">
                        <p class="text-muted">No reports found</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <!-- End Reports Table -->

            <!-- Users Table -->
            <section class="section">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Users</h5>
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead class="table-light">
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">Status</th>
                              <th scope="col">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                              <th scope="row">{{ index + 1 }}</th>
                              <td>{{ user.name }}</td>
                              <td>{{ user.email }}</td>
                              <td>
                                <span :class="`badge bg-${user.statusBadge}`">
                                  {{ user.statusText }}
                                </span>
                              </td>
                              <td>
                                <div class="dropdown">
                                  <button
                                    class="btn btn-secondary btn-sm dropdown-toggle compact-dropdown"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                  >
                                    Actions
                                  </button>
                                  <ul class="dropdown-menu compact-dropdown-menu">
                                    <li>
                                      <a
                                        class="dropdown-item"
                                        href="#"
                                        @click.prevent="viewUser(user.id)"
                                      >
                                        <i class="bi bi-eye me-2"></i>View
                                      </a>
                                    </li>
                                    <li>
                                      <a
                                        class="dropdown-item"
                                        href="#"
                                        @click.prevent="toggleUserStatus(user.id)"
                                      >
                                        <i
                                          class="bi"
                                          :class="[
                                            user.statusText === 'Active'
                                              ? 'bi-snow'
                                              : 'bi-snow-fill',
                                          ]"
                                        ></i>
                                        {{ user.statusText === "Active" ? "Block" : "Unblock" }}
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div v-if="users.length === 0" class="text-center py-4">
                        <p class="text-muted">No users found</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            <!-- End Users Table -->
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref } from "vue";

// Placeholder data for vendors
const vendors = ref([
  {
    id: 1,
    name: "Vendor One",
    email: "vendor1@example.com",
    statusText: "Active",
    statusBadge: "success",
  },
  {
    id: 2,
    name: "Vendor Two",
    email: "vendor2@example.com",
    statusText: "Blocked",
    statusBadge: "danger",
  },
]);

// Placeholder data for reports
const reports = ref([
  {
    id: 1,
    description: "Inappropriate content",
    reportedUser: "John Doe",
    statusText: "Resolved",
    statusBadge: "success",
    timestamp: "2023-10-01 14:30",
  },
  {
    id: 2,
    description: "Spam product",
    reportedUser: "Jane Smith",
    statusText: "Not Resolved",
    statusBadge: "danger",
    timestamp: "2023-10-02 10:15",
  },
]);

// Placeholder data for users
const users = ref([
  {
    id: 1,
    name: "Alice Johnson",
    email: "alice@example.com",
    statusText: "Active",
    statusBadge: "success",
  },
  {
    id: 2,
    name: "Bob Brown",
    email: "bob@example.com",
    statusText: "Blocked",
    statusBadge: "danger",
  },
]);

// Actions for Vendors
const viewVendor = (id) => {
  console.log(`View vendor with ID: ${id}`);
};

const toggleVendorStatus = (id) => {
  console.log(`Toggle vendor status with ID: ${id}`);
};

// Actions for Reports
const viewReport = (id) => {
  console.log(`View report with ID: ${id}`);
};

const resolveReport = (id) => {
  console.log(`Resolve report with ID: ${id}`);
};

// Actions for Users
const viewUser = (id) => {
  console.log(`View user with ID: ${id}`);
};

const toggleUserStatus = (id) => {
  console.log(`Toggle user status with ID: ${id}`);
};
</script>

<style scoped>
/* Compact dropdown styles */
.compact-dropdown {
  padding: 0.25rem 0.5rem; /* Smaller padding */
  font-size: 0.875rem; /* Smaller font size */
}

.compact-dropdown-menu {
  min-width: 120px; /* Smaller dropdown width */
  padding: 0.25rem 0; /* Smaller padding */
}

.compact-dropdown-menu .dropdown-item {
  padding: 0.25rem 0.75rem; /* Smaller padding for dropdown items */
  font-size: 0.875rem; /* Smaller font size for dropdown items */
}

.dropdown-item i {
  width: 1rem;
  margin-right: 0.5rem;
}
</style>

