<template>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Posts</h3>
                <p class="text-2xl font-bold">{{ stats.total_posts }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Categories</h3>
                <p class="text-2xl font-bold">{{ stats.total_categories }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
                <p class="text-2xl font-bold">{{ stats.total_users }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Comments</h3>
                <p class="text-2xl font-bold">{{ stats.total_comments }}</p>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Recent Activities</h2>
            <ul class="space-y-4">
                <li v-for="activity in recentActivities" :key="activity.id" class="border-b pb-2">
                    <div class="flex items-center">
                        <span class="text-gray-600">{{ activity.description }}</span>
                        <span class="text-sm text-gray-500 ml-auto">{{ formatDate(activity.created_at) }}</span>
                    </div>
                </li>
            </ul>
        </div>
        
        <!-- Recent Posts -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Recent Posts</h2>
            <div class="space-y-4">
                <div v-for="post in recentPosts" :key="post.id" class="border-b pb-4">
                    <h3 class="text-lg font-semibold">{{ post.title }}</h3>
                    <p class="text-gray-600 text-sm">{{ post.excerpt }}</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <span>{{ post.user.name }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ formatDate(post.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            stats: {
                total_posts: 0,
                total_categories: 0,
                total_users: 0,
                total_comments: 0
            },
            recentActivities: [],
            recentPosts: []
        };
    },
    methods: {
        async fetchStats() {
            try {
                const response = await fetch('/api/dashboard/stats');
                const data = await response.json();
                this.stats = data;
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        },
        async fetchRecentActivities() {
            try {
                const response = await fetch('/api/dashboard/activities');
                const data = await response.json();
                this.recentActivities = data;
            } catch (error) {
                console.error('Error fetching activities:', error);
            }
        },
        async fetchRecentPosts() {
            try {
                const response = await fetch('/api/dashboard/posts');
                const data = await response.json();
                this.recentPosts = data;
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        },
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }
    },
    mounted() {
        this.fetchStats();
        this.fetchRecentActivities();
        this.fetchRecentPosts();
    }
};
</script>