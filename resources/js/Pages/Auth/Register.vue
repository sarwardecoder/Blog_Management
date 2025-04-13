<template>
  <div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Register</h1>
    
    <form @submit.prevent="submitRegister" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Username</label>
        <input 
          v-model="form.username" 
          type="text" 
          name="username"
          class="w-full border p-2 rounded" 
          required
        >
      </div>
      
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Email</label>
        <input 
          v-model="form.email" 
          type="email" 
          name="email"
          class="w-full border p-2 rounded" 
          required
        >
      </div>
      
      <div class="mb-4">
        <label class="block text-gray-700 mb-2">Password</label>
        <input 
          v-model="form.password" 
          type="password" 
          name="password"
          class="w-full border p-2 rounded" 
          required
        >
      </div>
      
      <button 
        type="submit" 
        class="bg-blue-500 text-white px-4 py-2 rounded w-full"
      >
        Register
      </button>
      
      <div v-if="error" class="mt-4 text-red-500">
        {{ error }}
      </div>
    </form>
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