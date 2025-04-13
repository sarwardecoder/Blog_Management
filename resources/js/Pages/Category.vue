<template>
    <div class="container mx-auto p-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold">Categories</h1>
            <button
                @click="showCreateForm = true"
                class="bg-blue-500 text-white px-4 py-2 rounded mt-2"
            >
                Add New Category
            </button>
        </div>

        <!-- Categories List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="category in categories"
                :key="category.id"
                class="bg-white p-4 rounded-lg shadow"
            >
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">{{ category.name }}</h2>
                    <div class="flex gap-2">
                        <button
                            @click="editCategory(category)"
                            class="text-blue-500"
                        >
                            Edit
                        </button>
                        <button
                            @click="deleteCategory(category.id)"
                            class="text-red-500"
                        >
                            Delete
                        </button>
                    </div>
                </div>
                <p class="text-gray-600 mt-2">{{ category.description }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    {{ category.posts_count || 0 }} posts
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div
            v-if="showCreateForm"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded-lg w-full max-w-md">
                <h2 class="text-2xl font-bold mb-4">
                    {{ editingCategory ? "Edit Category" : "Add New Category" }}
                </h2>
                <form @submit.prevent="submitCategory">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="categoryForm.name"
                            type="text"
                            class="w-full border p-2 rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2"
                            >Description</label
                        >
                        <textarea
                            v-model="categoryForm.description"
                            class="w-full border p-2 rounded h-24"
                        ></textarea>
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
                            {{ editingCategory ? "Update" : "Create" }}
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
            categories: [],
            showCreateForm: false,
            editingCategory: null,
            categoryForm: {
                name: "",
                description: "",
            },
        };
    },
    methods: {
        async fetchCategories() {
            try {
                const response = await fetch("/api/categories");
                const data = await response.json();
                this.categories = data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        editCategory(category) {
            this.editingCategory = category;
            this.categoryForm = {
                name: category.name,
                description: category.description,
            };
            this.showCreateForm = true;
        },
        async submitCategory() {
            try {
                const url = this.editingCategory
                    ? `/api/categories/${this.editingCategory.id}`
                    : "/api/categories";
                const method = this.editingCategory ? "PUT" : "POST";

                const response = await fetch(url, {
                    method,
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(this.categoryForm),
                });

                if (response.ok) {
                    this.showCreateForm = false;
                    this.editingCategory = null;
                    this.resetForm();
                    await this.fetchCategories();
                }
            } catch (error) {
                console.error("Error submitting category:", error);
            }
        },
        async deleteCategory(id) {
            if (confirm("Are you sure you want to delete this category?")) {
                try {
                    const response = await fetch(`/api/categories/${id}`, {
                        method: "DELETE",
                    });

                    if (response.ok) {
                        await this.fetchCategories();
                    }
                } catch (error) {
                    console.error("Error deleting category:", error);
                }
            }
        },
        resetForm() {
            this.categoryForm = {
                name: "",
                description: "",
            };
        },
    },
    mounted() {
        this.fetchCategories();
    },
};
</script>
