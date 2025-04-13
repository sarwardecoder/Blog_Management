<template>
  <div class="container mx-auto p-4">
    <div class="mb-6">
      <h1 class="text-3xl font-bold">Comments</h1>
    </div>

    <!-- Comments List -->
    <div class="space-y-4">
      <div v-for="comment in comments" :key="comment.id" class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-start">
          <div>
            <div class="font-semibold">{{ comment.user_name }}</div>
            <p class="text-gray-600 mt-1">{{ comment.content }}</p>
            <div class="text-sm text-gray-500 mt-2">{{ comment.created_at }}</div>
          </div>
          <div class="flex gap-2">
            <button @click="replyToComment(comment)" class="text-blue-500">Reply</button>
            <button v-if="isUserComment(comment)" @click="editComment(comment)" class="text-blue-500">Edit</button>
            <button v-if="isUserComment(comment)" @click="deleteComment(comment.id)" class="text-red-500">Delete</button>
          </div>
        </div>

        <!-- Nested Replies -->
        <div v-if="comment.replies && comment.replies.length > 0" class="ml-8 mt-4 space-y-4">
          <div v-for="reply in comment.replies" :key="reply.id" class="bg-gray-50 p-3 rounded">
            <div class="flex justify-between items-start">
              <div>
                <div class="font-semibold">{{ reply.user_name }}</div>
                <p class="text-gray-600 mt-1">{{ reply.content }}</p>
                <div class="text-sm text-gray-500 mt-2">{{ reply.created_at }}</div>
              </div>
              <div class="flex gap-2">
                <button v-if="isUserComment(reply)" @click="editComment(reply)" class="text-blue-500">Edit</button>
                <button v-if="isUserComment(reply)" @click="deleteComment(reply.id)" class="text-red-500">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Reply Form -->
        <div v-if="replyingTo === comment.id" class="mt-4 ml-8">
          <form @submit.prevent="submitReply(comment.id)" class="space-y-2">
            <textarea 
              v-model="replyForm.content" 
              class="w-full border p-2 rounded"
              placeholder="Write your reply..."
              required
            ></textarea>
            <div class="flex justify-end gap-2">
              <button type="button" @click="cancelReply" class="text-gray-500">Cancel</button>
              <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Reply</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Comment Modal -->
    <div v-if="showEditForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Edit Comment</h2>
        <form @submit.prevent="submitEdit">
          <div class="mb-4">
            <textarea 
              v-model="editForm.content" 
              class="w-full border p-2 rounded h-24"
              required
            ></textarea>
          </div>
          <div class="flex justify-end gap-2">
            <button type="button" @click="showEditForm = false" class="bg-gray-500 text-white px-4 py-2 rounded">
              Cancel
            </button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
              Update
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
      comments: [],
      showEditForm: false,
      editingComment: null,
      replyingTo: null,
      editForm: {
        content: ''
      },
      replyForm: {
        content: ''
      }
    }
  },
  methods: {
    async fetchComments() {
      try {
        const response = await fetch('/api/comments')
        const data = await response.json()
        this.comments = data
      } catch (error) {
        console.error('Error fetching comments:', error)
      }
    },
    replyToComment(comment) {
      this.replyingTo = comment.id
      this.replyForm.content = ''
    },
    cancelReply() {
      this.replyingTo = null
      this.replyForm.content = ''
    },
    editComment(comment) {
      this.editingComment = comment
      this.editForm.content = comment.content
      this.showEditForm = true
    },
    async submitReply(parentId) {
      try {
        const response = await fetch('/api/comments', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            content: this.replyForm.content,
            parent_id: parentId
          })
        })

        if (response.ok) {
          this.replyingTo = null
          this.replyForm.content = ''
          await this.fetchComments()
        }
      } catch (error) {
        console.error('Error submitting reply:', error)
      }
    },
    async submitEdit() {
      try {
        const response = await fetch(`/api/comments/${this.editingComment.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(this.editForm)
        })

        if (response.ok) {
          this.showEditForm = false
          this.editingComment = null
          this.editForm.content = ''
          await this.fetchComments()
        }
      } catch (error) {
        console.error('Error updating comment:', error)
      }
    },
    async deleteComment(id) {
      if (confirm('Are you sure you want to delete this comment?')) {
        try {
          const response = await fetch(`/api/comments/${id}`, {
            method: 'DELETE'
          })

          if (response.ok) {
            await this.fetchComments()
          }
        } catch (error) {
          console.error('Error deleting comment:', error)
        }
      }
    },
    isUserComment(comment) {
      // Compare with current user ID
      return comment.user_id === this.$store.state.user.id
    }
  },
  mounted() {
    this.fetchComments()
  }
}
</script>