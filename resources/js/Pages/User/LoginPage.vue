<script setup>
</script>

<template>
 <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h1 class="h2 mb-4">Create User</h1>
        <div v-if="successMessage" class="alert alert-success mb-4">
          {{ successMessage }}
        </div>
        <form @submit.prevent="submit" class="mb-3">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input 
              id="username" 
              v-model="form.username" 
              type="text" 
              class="form-control" 
              required 
            />
            <div v-if="errors.username" class="text-danger small mt-1">{{ errors.username }}</div>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
              id="email" 
              v-model="form.email" 
              type="email" 
              class="form-control" 
              required 
            />
            <div v-if="errors.email" class="text-danger small mt-1">{{ errors.email }}</div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
              id="password" 
              v-model="form.password" 
              type="password" 
              class="form-control" 
              required 
              minlength="8" 
            />
            <div v-if="errors.password" class="text-danger small mt-1">{{ errors.password }}</div>
          </div>
          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input 
              id="password_confirmation" 
              v-model="form.password_confirmation" 
              type="password" 
              class="form-control" 
              required 
              minlength="8" 
            />
          </div>
          <button 
            type="submit" 
            :disabled="form.processing" 
            class="btn btn-primary w-100"
          >
            <span v-if="form.processing">Creating...</span>
            <span v-else>Create</span>
          </button>
        </form>
      </div>
    </div>
  </div>    
</template>

<style scoped>
</style>