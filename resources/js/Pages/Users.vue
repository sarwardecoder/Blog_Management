<template>
    <div class="container mx-auto p-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold">Users</h1>
            <button
                @click="showCreateForm = true"
                class="bg-blue-500 text-white px-4 py-2 rounded mt-2"
            >
                Add New User
            </button>
        </div>

        <!-- Users List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in users" :key="user.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                  :class="{
                                    'bg-green-100 text-green-800': user.role === 'admin',
                                    'bg-blue-100 text-blue-800': user.role === 'editor',
                                    'bg-gray-100 text-gray-800': user.role === 'user'
                                  }">
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="editUser(user)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Create/Edit Modal -->
        <div
            v-if="showCreateForm"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-2xl font-bold mb-4">
                    {{ editingUser ? 'Edit User' : 'Add New User' }}
                </h2>
                <form @submit.prevent="submitUser" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="userForm.name"
                            type="text"
                            class="w-full border p-2 rounded"
                            required
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Email</label>
                        <input
                            v-model="userForm.email"
                            type="email"
                            class="w-full border p-2 rounded"
                            required
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Role</label>
                        <select
                            v-model="userForm.role"
                            class="w-full border p-2 rounded"
                            required
                        >
                            <option value="admin">Admin</option>
                            <option value="editor">Editor</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div v-if="!editingUser">
                        <label class="block text-gray-700 mb-2">Password</label>
                        <input
                            v-model="userForm.password"
                            type="password"
                            class="w-full border p-2 rounded"
                            required
                        />
                    </div>
                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="showCreateForm = false"
                            class="bg-gray-500 text-white px-4 py-2 rounded"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            {{ editingUser ? 'Update' : 'Create' }}
                        </button>
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
            users: [],
            showCreateForm: false,
            editingUser: null,
            userForm: {
                name: '',
                email: '',
                role: 'user',
                password: ''
            }
        };
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await fetch('/api/users');
                const data = await response.json();
                this.users = data;
            } catch (error) {
                console.error('Error fetching users:', error);
            }
        },
        editUser(user) {
            this.editingUser = user;
            this.userForm = {
                name: user.name,
                email: user.email,
                role: user.role,
                password: ''
            };
            this.showCreateForm = true;
        },
        async submitUser() {
            try {
                const url = this.editingUser 
                    ? `/api/users/${this.editingUser.id}` 
                    : '/api/users';
                const method = this.editingUser ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(this.userForm)
                });

                if (response.ok) {
                    this.showCreateForm = false;
                    this.editingUser = null;
                    this.resetForm();
                    await this.fetchUsers();
                }
            } catch (error) {
                console.error('Error submitting user:', error);
            }
        },
        async deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                try {
                    const response = await fetch(`/api/users/${id}`, {
                        method: 'DELETE'
                    });

                    if (response.ok) {
                        await this.fetchUsers();
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                }
            }
        },
        resetForm() {
            this.userForm = {
                name: '',
                email: '',
                role: 'user',
                password: ''
            };
        }
    },
    mounted() {
        this.fetchUsers();
    }
};
</script>