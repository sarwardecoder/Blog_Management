<template>
  <div class="container mx-auto p-4 max-w-md">
    <h1 class="text-2xl font-bold mb-6">Edit User</h1>
    <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ successMessage }}
    </div>
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label for="username" class="block text-gray-700 mb-1">Username</label>
        <input 
          id="username" 
          v-model="form.username" 
          type="text" 
          class="w-full border p-2 rounded" 
          required 
        />
        <div v-if="errors.username" class="text-red-500 text-sm mt-1">{{ errors.username }}</div>
      </div>
      <div>
        <label for="email" class="block text-gray-700 mb-1">Email</label>
        <input 
          id="email" 
          v-model="form.email" 
          type="email" 
          class="w-full border p-2 rounded" 
          required 
        />
        <div v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</div>
      </div>
      <div>
        <label for="password" class="block text-gray-700 mb-1">Password</label>
        <input 
          id="password" 
          v-model="form.password" 
          type="password" 
          class="w-full border p-2 rounded" 
          placeholder="Leave blank to keep current password"
        />
        <div v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</div>
      </div>
      <button 
        type="submit" 
        :disabled="form.processing" 
        class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors"
      >
        <span v-if="form.processing">Updating...</span>
        <span v-else>Update</span>
      </button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});

const successMessage = ref('');
const form = useForm({
  username: props.user.username,
  email: props.user.email,
  password: ''
});

const submit = () => {
  form.put(`/users/${props.user.id}`, {
    onSuccess: () => {
      successMessage.value = 'User updated successfully!';
    },
    onError: () => {
      successMessage.value = '';
    }
  });
};
</script>

<style scoped>
.error {
  color: red;
  font-size: 0.8rem;
}
</style>