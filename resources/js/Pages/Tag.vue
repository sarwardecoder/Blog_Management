<template>
  <div class="container mx-auto p-4">
    <div class="mb-6">
      <h1 class="text-3xl font-bold">Tags</h1>
      <button @click="showCreateForm = true" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
        Add New Tag
      </button>
    </div>

    <!-- Tags List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="tag in tags" :key="tag.id" class="bg-white p-4 rounded-lg shadow">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-xl font-semibold">{{ tag.name }}</h2>
            <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 mt-2">
              {{ tag.posts_count || 0 }} posts
            </span>
          </div>
          <div class="flex gap-2">
            <button @click="editTag(tag)" class="text-blue-500">Edit</button>
            <button @click="deleteTag(tag.id)" class="text-red-500">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">{{ editingTag ? 'Edit Tag' : 'Add New Tag' }}</h2>
        <form @submit.prevent="submitTag">
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Name</label>
            <input v-model="tagForm.name" type="text" class="w-full border p-2 rounded" required>
          </div>
          <div class="flex justify-end gap-2">
            <button type="button" @click="showCreateForm = false" class="bg-gray-500 text-white px-4 py-2 rounded">
              Cancel
            </button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
              {{ editingTag ? 'Update' : 'Create' }}
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
      tags: [],
      showCreateForm: false,
      editingTag: null,
      tagForm: {
        name: ''
      }
    }
  },
  methods: {
    async fetchTags() {
      try {
        const response = await fetch('/api/tags')
        const data = await response.json()
        this.tags = data
      } catch (error) {
        console.error('Error fetching tags:', error)
      }
    },
    editTag(tag) {
      this.editingTag = tag
      this.tagForm = {
        name: tag.name
      }
      this.showCreateForm = true
    },
    async submitTag() {
      try {
        const url = this.editingTag 
          ? `/api/tags/${this.editingTag.id}` 
          : '/api/tags'
        const method = this.editingTag ? 'PUT' : 'POST'
        
        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(this.tagForm)
        })

        if (response.ok) {
          this.showCreateForm = false
          this.editingTag = null
          this.resetForm()
          await this.fetchTags()
        }
      } catch (error) {
        console.error('Error submitting tag:', error)
      }
    },
    async deleteTag(id) {
      if (confirm('Are you sure you want to delete this tag?')) {
        try {
          const response = await fetch(`/api/tags/${id}`, {
            method: 'DELETE'
          })

          if (response.ok) {
            await this.fetchTags()
          }
        } catch (error) {
          console.error('Error deleting tag:', error)
        }
      }
    },
    resetForm() {
      this.tagForm = {
        name: ''
      }
    }
  },
  mounted() {
    this.fetchTags()
  }
}
</script>