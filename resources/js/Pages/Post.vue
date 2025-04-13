<template>
    <div class="container mx-auto p-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold">Posts</h1>
            <button
                @click="showCreateForm = true"
                class="bg-blue-500 text-white px-4 py-2 rounded mt-2"
            >
                Add New Post
            </button>
        </div>

        <!-- Posts List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="post in posts"
                :key="post.id"
                class="bg-white p-4 rounded-lg shadow"
            >
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">{{ post.title }}</h2>
                    <div class="flex gap-2">
                        <button
                            @click="editPost(post)"
                            class="text-blue-500"
                        >
                            Edit
                        </button>
                        <button
                            @click="deletePost(post.id)"
                            class="text-red-500"
                        >
                            Delete
                        </button>
                    </div>
                </div>
                <p class="text-gray-600 mt-2">{{ post.excerpt }}</p>
                <div class="mt-2 text-sm text-gray-500">
                    <span>Category: {{ post.category?.name || 'Uncategorized' }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ post.comments_count || 0 }} comments</span>
                </div>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span
                        v-for="tag in post.tags"
                        :key="tag.id"
                        class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-sm"
                    >
                        {{ tag.name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div
            v-if="showCreateForm"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center overflow-y-auto"
        >
            <div class="bg-white p-6 rounded-lg w-full max-w-2xl my-8">
                <h2 class="text-2xl font-bold mb-4">
                    {{ editingPost ? "Edit Post" : "Add New Post" }}
                </h2>
                <form @submit.prevent="submitPost" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Title</label>
                        <input
                            v-model="postForm.title"
                            type="text"
                            class="w-full border p-2 rounded"
                            required
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Content</label>
                        <textarea
                            v-model="postForm.content"
                            class="w-full border p-2 rounded h-48"
                            required
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Category</label>
                        <select
                            v-model="postForm.category_id"
                            class="w-full border p-2 rounded"
                            required
                        >
                            <option value="">Select a category</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Tags</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span
                                v-for="tag in selectedTags"
                                :key="tag.id"
                                class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-sm flex items-center"
                            >
                                {{ tag.name }}
                                <button
                                    @click.prevent="removeTag(tag)"
                                    class="ml-2 text-blue-500 hover:text-blue-700"
                                >
                                    ×
                                </button>
                            </span>
                        </div>
                        <select
                            v-model="selectedTag"
                            @change="addTag"
                            class="w-full border p-2 rounded"
                        >
                            <option value="">Add a tag</option>
                            <option
                                v-for="tag in availableTags"
                                :key="tag.id"
                                :value="tag"
                            >
                                {{ tag.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Visibility</label>
                        <select
                            v-model="postForm.visibility"
                            class="w-full border p-2 rounded"
                            required
                        >
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                            <option value="draft">Draft</option>
                        </select>
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
                            {{ editingPost ? "Update" : "Create" }}
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
            posts: [],
            categories: [],
            tags: [],
            showCreateForm: false,
            editingPost: null,
            selectedTags: [],
            selectedTag: "",
            postForm: {
                title: "",
                content: "",
                category_id: "",
                visibility: "public",
                tags: [],
            },
        };
    },
    computed: {
        availableTags() {
            return this.tags.filter(
                (tag) =>
                    !this.selectedTags.some(
                        (selectedTag) => selectedTag.id === tag.id
                    )
            );
        },
    },
    methods: {
        async fetchPosts() {
            try {
                const response = await fetch("/api/posts");
                const data = await response.json();
                this.posts = data;
            } catch (error) {
                console.error("Error fetching posts:", error);
            }
        },
        async fetchCategories() {
            try {
                const response = await fetch("/api/categories");
                const data = await response.json();
                this.categories = data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        async fetchTags() {
            try {
                const response = await fetch("/api/tags");
                const data = await response.json();
                this.tags = data;
            } catch (error) {
                console.error("Error fetching tags:", error);
            }
        },
        editPost(post) {
            this.editingPost = post;
            this.postForm = {
                title: post.title,
                content: post.content,
                category_id: post.category_id,
                visibility: post.visibility,
                tags: post.tags,
            };
            this.selectedTags = [...post.tags];
            this.showCreateForm = true;
        },
        async submitPost() {
            try {
                const url = this.editingPost
                    ? `/api/posts/${this.editingPost.id}`
                    : "/api/posts";
                const method = this.editingPost ? "PUT" : "POST";

                const formData = {
                    ...this.postForm,
                    tags: this.selectedTags.map((tag) => tag.id),
                };

                const response = await fetch(url, {
                    method,
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(formData),
                });

                if (response.ok) {
                    this.showCreateForm = false;
                    this.editingPost = null;
                    this.resetForm();
                    await this.fetchPosts();
                }
            } catch (error) {
                console.error("Error submitting post:", error);
            }
        },
        async deletePost(id) {
            if (confirm("Are you sure you want to delete this post?")) {
                try {
                    const response = await fetch(`/api/posts/${id}`, {
                        method: "DELETE",
                    });

                    if (response.ok) {
                        await this.fetchPosts();
                    }
                } catch (error) {
                    console.error("Error deleting post:", error);
                }
            }
        },
        addTag() {
            if (this.selectedTag && !this.selectedTags.includes(this.selectedTag)) {
                this.selectedTags.push(this.selectedTag);
                this.selectedTag = "";
            }
        },
        removeTag(tag) {
            this.selectedTags = this.selectedTags.filter((t) => t.id !== tag.id);
        },
        resetForm() {
            this.postForm = {
                title: "",
                content: "",
                category_id: "",
                visibility: "public",
                tags: [],
            };
            this.selectedTags = [];
            this.selectedTag = "";
        },
    },
    mounted() {
        this.fetchPosts();
        this.fetchCategories();
        this.fetchTags();
    },
};
</script>