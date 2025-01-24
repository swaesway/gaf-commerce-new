<template>
    <div class="main">
      <div class="pagetitle">
        <h1>My Profile</h1>
        
      </div>
  
      <section class="section profile">
        <div class="row">
          <div class="col-xl-4">
            <div class="card">
              <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                <img :src="profileImage" alt="Profile" class="imgprofile">
                <h2>{{ userName }}</h2>
              </div>
            </div>
          </div>
  
          <div class="col-xl-8">
            <div class="card">
              <div class="card-body pt-3">
                <ul class="nav nav-tabs nav-tabs-bordered">
                  <li class="nav-item">
                    <button 
                      class="nav-link" 
                      :class="{ active: activeTab === 'edit' }"
                      @click="activeTab = 'edit'"
                    >
                      Edit Profile
                    </button>
                  </li>
                  <li class="nav-item">
                    <button 
                      class="nav-link" 
                      :class="{ active: activeTab === 'settings' }"
                      @click="activeTab = 'settings'"
                    >
                      Settings
                    </button>
                  </li>
                  <li class="nav-item">
                    <button 
                      class="nav-link" 
                      :class="{ active: activeTab === 'password' }"
                      @click="activeTab = 'password'"
                    >
                      Change Password
                    </button>
                  </li>
                </ul>
  
                <div class="tab-content pt-2">
                  <!-- Edit Profile Tab -->
                  <div v-if="activeTab === 'edit'" class="profile-edit pt-3">
                    <form @submit.prevent="saveProfile">
                      <div class="row mb-3">
                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                        <div class="col-md-8 col-lg-9">
                          <img :src="profileImage" alt="Profile" class="imgprofile">
                          <div class="pt-2">
                            <button type="button" class="btn btn-primary btn-sm" title="Upload new profile image">
                              <i class="bi bi-upload"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" title="Remove my profile image">
                              <i class="bi bi-trash"></i>
                            </button>
                          </div>
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Shop Name</label>
                        <div class="col-md-8 col-lg-9">
                          <input v-model="user.name" type="text" class="form-control" id="fullName">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                        <div class="col-md-8 col-lg-9">
                          <input v-model="user.phone" type="text" class="form-control" id="Phone">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                        <div class="col-md-8 col-lg-9">
                          <input v-model="user.email" type="email" class="form-control" id="Email">
                        </div>
                      </div>
  
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
                  </div>
  
                  <!-- Settings Tab -->
                  <div v-if="activeTab === 'settings'" class="pt-3">
                    <form @submit.prevent="saveSettings">
                      <div class="row mb-3">
                        <label class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                        <div class="col-md-8 col-lg-9">
                          <div class="form-check">
                            <input v-model="settings.accountChanges" class="form-check-input" type="checkbox" id="changesMade">
                            <label class="form-check-label" for="changesMade">
                              Changes made to your account
                            </label>
                          </div>
                          <div class="form-check">
                            <input v-model="settings.newProducts" class="form-check-input" type="checkbox" id="newProducts">
                            <label class="form-check-label" for="newProducts">
                              Information on new products and services
                            </label>
                          </div>
                          <div class="form-check">
                            <input v-model="settings.proOffers" class="form-check-input" type="checkbox" id="proOffers">
                            <label class="form-check-label" for="proOffers">
                              Marketing and promo offers
                            </label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                            <label class="form-check-label" for="securityNotify">
                              Security alerts
                            </label>
                          </div>
                        </div>
                      </div>
  
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
                  </div>
  
                  <!-- Change Password Tab -->
                  <div v-if="activeTab === 'password'" class="pt-3">
                    <form @submit.prevent="changePassword">
                      <div class="row mb-3">
                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                        <div class="col-md-8 col-lg-9">
                          <input v-model="password.current" type="password" class="form-control" id="currentPassword">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                        <div class="col-md-8 col-lg-9">
                          <input v-model="password.new" type="password" class="form-control" id="newPassword">
                        </div>
                      </div>
  
                      <div class="row mb-3">
                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                        <div class="col-md-8 col-lg-9">
                          <input v-model="password.confirm" type="password" class="form-control" id="renewPassword">
                        </div>
                      </div>
  
                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  // Active tab state
  const activeTab = ref('edit')
  
  // User profile data
  const userName = ref('Kevin Anderson')
  const profileImage = ref('/api/placeholder/100/100')
  
  // User details
  const user = ref({
    name: 'Kevin Anderson',
    phone: '(436) 486-3538 x29071',
    email: 'k.anderson@example.com'
  })
  
  // Settings
  const settings = ref({
    accountChanges: true,
    newProducts: true,
    proOffers: false
  })
  
  // Password management
  const password = ref({
    current: '',
    new: '',
    confirm: ''
  })
  
  // Form submission methods
  const saveProfile = () => {
    // Implement profile save logic
    console.log('Profile saved', user.value)
  }
  
  const saveSettings = () => {
    // Implement settings save logic
    console.log('Settings saved', settings.value)
  }
  
  const changePassword = () => {
    // Implement password change logic
    if (password.value.new === password.value.confirm) {
      console.log('Password changed')
      // Reset password fields
      password.value.current = ''
      password.value.new = ''
      password.value.confirm = ''
    } else {
      console.error('Passwords do not match')
    }
  }
  </script>
  
  <style scoped>
  .imgprofile {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border: 2px solid #f2f2f2;
  }
  </style>