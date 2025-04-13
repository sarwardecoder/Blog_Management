<template>
  <div class="container mx-auto p-4 max-w-2xl">
    <div class="bg-white rounded-lg shadow-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">User Details</h1>
        <div class="space-x-2">
          <Link
            :href="`/users/${user.id}/edit`"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors"
          >
            Edit
          </Link>
          <button
            @click="confirmDelete"
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors"
          >
            Delete
          </button>
        </div>
      </div>

      <div class="space-y-4">
        <div class="border-b pb-4">
          <h2 class="text-gray-600 text-sm">Username</h2>
          <p class="text-gray-900">{{ user.username }}</p>
        </div>

        <div class="border-b pb-4">
          <h2 class="text-gray-600 text-sm">Email</h2>
          <p class="text-gray-900">{{ user.email }}</p>
        </div>

        <div class="border-b pb-4">
          <h2 class="text-gray-600 text-sm">Created At</h2>
          <p class="text-gray-900">{{ new Date(user.created_at).toLocaleDateString() }}</p>
        </div>

        <div>
          <h2 class="text-gray-600 text-sm">Last Updated</h2>
          <p class="text-gray-900">{{ new Date(user.updated_at).toLocaleDateString() }}</p>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
        <p class="mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="flex justify-end space-x-2">
          <button
            @click="showDeleteModal = false"
            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="deleteUser"
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});

const showDeleteModal = ref(false);

const confirmDelete = () => {
  showDeleteModal.value = true;
};

const deleteUser = () => {
  router.delete(`/users/${props.user.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
    }
  });
};
</script>