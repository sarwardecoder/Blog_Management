<template>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Search Posts</h2>
debugger;
            <!-- Search Form -->
            <form @submit.prevent="searchPosts" class="space-y-4">
                <!-- General Search -->
                <div>
                    <label class="block text-gray-700 mb-2"
                        >Search in Title & Content</label
                    >
                    <input
                        v-model="searchForm.search"
                        type="text"
                        class="w-full border p-2 rounded"
                        placeholder="Search posts..."
                    />
                </div>

                <!-- Username Filter -->
                <div>
                    <label class="block text-gray-700 mb-2"
                        >Filter by Username</label
                    >
                    <input
                        v-model="searchForm.username"
                        type="text"
                        class="w-full border p-2 rounded"
                        placeholder="Enter username..."
                    />
                </div>

                <!-- Tags Filter -->
                <div>
                    <label class="block text-gray-700 mb-2"
                        >Filter by Tags</label
                    >
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span
                            v-for="tag in selectedTags"
                            :key="tag.id"
                            class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm flex items-center"
                        >
                            {{ tag.name }}
                            <button
                                @click.prevent="removeTag(tag)"
                                class="ml-2 text-blue-600 hover:text-blue-800"
                            >
                                &times;
                            </button>
                        </span>
                    </div>
                    <select
                        v-model="selectedTag"
                        @change="addTag"
                        class="w-full border p-2 rounded"
                    >
                        <option value="">Select a tag...</option>
                        <option
                            v-for="tag in availableTags"
                            :key="tag.id"
                            :value="tag"
                        >
                            {{ tag.name }}
                        </option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        <div v-if="posts.length > 0" class="space-y-6">
            <div
                v-for="post in posts"
                :key="post.id"
                class="bg-white rounded-lg shadow p-6"
            >
                <h3 class="text-xl font-bold mb-2">
                    <a
                        :href="`/posts/${post.id}`"
                        class="text-blue-600 hover:text-blue-800"
                        >{{ post.title }}</a
                    >
                </h3>
                <div class="text-gray-600 mb-4">
                    <span>By {{ post.user.name }}</span>
                    <span class="mx-2">•</span>
                    <span>{{
                        new Date(post.created_at).toLocaleDateString()
                    }}</span>
                </div>
                <p class="text-gray-700 mb-4">
                    {{ post.content.substring(0, 200) }}...
                </p>
                <div class="flex flex-wrap gap-2">
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
        <div
            v-else-if="searched"
            class="bg-white rounded-lg shadow p-6 text-center text-gray-600"
        >
            No posts found matching your search criteria.
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchForm: {
                search: "",
                username: "",
                tags: "",
            },
            posts: [],
            tags: [],
            selectedTags: [],
            selectedTag: "",
            searched: false,
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
        async searchPosts() {
            try {
                const queryParams = new URLSearchParams();
                if (this.searchForm.search)
                    queryParams.append("search", this.searchForm.search);
                if (this.searchForm.username)
                    queryParams.append("username", this.searchForm.username);
                if (this.selectedTags.length > 0) {
                    queryParams.append(
                        "tags",
                        this.selectedTags.map((tag) => tag.name).join(",")
                    );
                }

                const response = await fetch(
                    `/api/search?${queryParams.toString()}`
                );
                const data = await response.json();
                this.posts = data.posts.data;
                this.searched = true;
            } catch (error) {
                console.error("Error searching posts:", error);
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
        addTag() {
            if (
                this.selectedTag &&
                !this.selectedTags.includes(this.selectedTag)
            ) {
                this.selectedTags.push(this.selectedTag);
                this.selectedTag = "";
            }
        },
        removeTag(tag) {
            this.selectedTags = this.selectedTags.filter(
                (t) => t.id !== tag.id
            );
        },
    },
    mounted() {
        this.fetchTags();
    },
};
</script>
