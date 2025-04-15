<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h1 class="h2 mb-4">Register</h1>
        
        <form @submit.prevent="submitRegister" class="card p-4 shadow-sm">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input 
              v-model="form.username" 
              type="text" 
              name="username"
              class="form-control" 
              required
            >
          </div>
          
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input 
              v-model="form.email" 
              type="email" 
              name="email"
              class="form-control" 
              required
            >
          </div>
          
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input 
              v-model="form.password" 
              type="password" 
              name="password"
              class="form-control" 
              required
            >
          </div>
          
          <button 
            type="submit" 
            class="btn btn-primary w-100"
          >
            Register
          </button>
          
          <div v-if="error" class="alert alert-danger mt-3">
            {{ error }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        username: '',
        email: '',
        password: ''
      },
      error: ''
    }
  },
  methods: {
    async submitRegister() {
      try {
        const response = await axios.post('/register', this.form)
        if (response.data.status === 'success') {
          // Handle successful registration
          this.$router.push('/login')
        } else {
          this.error = response.data.message
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Registration failed'
      }
    }
  }
}
</script>