<template>
  <div class="container mx-auto p-4">
    <div class="mb-6">
      <h1 class="text-3xl font-bold">My Bookmarks</h1>
    </div>

    <!-- Bookmarked Posts List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="bookmark in bookmarks" :key="bookmark.id" class="bg-white p-4 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-2">{{ bookmark.post.title }}</h2>
        <p class="text-gray-600 mb-4">{{ bookmark.post.excerpt }}</p>
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-500">Bookmarked on {{ bookmark.created_at }}</span>
          <div class="flex gap-2">
            <button @click="viewPost(bookmark.post.id)" class="text-blue-500">View Post</button>
            <button @click="removeBookmark(bookmark.id)" class="text-red-500">Remove</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="bookmarks.length === 0" class="text-center py-8">
      <p class="text-gray-600">You haven't bookmarked any posts yet.</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      bookmarks: []
    }
  },
  methods: {
    async fetchBookmarks() {
      try {
        const response = await fetch('/api/bookmarks')
        const data = await response.json()
        this.bookmarks = data
      } catch (error) {
        console.error('Error fetching bookmarks:', error)
      }
    },
    viewPost(postId) {
      // Navigate to the post detail page
      this.$router.push(`/posts/${postId}`)
    },
    async removeBookmark(id) {
      if (confirm('Are you sure you want to remove this bookmark?')) {
        try {
          const response = await fetch(`/api/bookmarks/${id}`, {
            method: 'DELETE'
          })

          if (response.ok) {
            await this.fetchBookmarks()
          }
        } catch (error) {
          console.error('Error removing bookmark:', error)
        }
      }
    }
  },
  mounted() {
    this.fetchBookmarks()
  }
}
</script>