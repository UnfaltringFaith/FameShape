<template>
  <div class="max-w-2xl mx-auto px-4 py-10">
    <div v-if="loading" class="flex justify-center items-center py-20">
      <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
      <span class="ml-4 text-gray-600 text-lg">Загрузка...</span>
    </div>
    <div v-else-if="error" class="text-center text-red-600 py-20">
      {{ error }}
    </div>
    <div v-else-if="post" class="bg-white rounded-xl shadow-md p-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ post.title }}</h1>
      <div class="flex items-center text-gray-500 text-sm mb-6">
        <span v-if="post.user" class="flex items-center mr-4">
          <span class="text-lg mr-1">👤</span>
          {{ post.user.name }}
        </span>
        <span class="mr-4">🗓 {{ formatDate(post.created_at) }}</span>
        <span class="mr-4">👁 {{ post.views || 0 }}</span>
      </div>
      <div v-if="post.image" class="mb-6">
        <img :src="`/storage/${post.image}`" :alt="post.title" class="w-full rounded-lg object-cover max-h-96" />
      </div>
      <div class="prose prose-lg max-w-none text-gray-800 mb-6" v-html="post.content"></div>
      <div v-if="post.tags && post.tags.length > 0" class="mb-4">
        <div class="flex flex-wrap gap-2">
          <span v-for="tag in post.tags" :key="tag.id"
            class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
            #{{ tag.name }}
          </span>
        </div>
      </div>
      <button class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors"
        @click="dislikePost">👎</button>
      <span>❤️ {{ post.likes || 0 }}</span>
      <button class="px-3 py-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors"
        @click="likePost">👍</button>
      <div v-if="!post.is_published" class="mt-6 text-yellow-700 bg-yellow-100 rounded px-4 py-2 text-sm">
        Пост ещё не опубликован
      </div>
    </div>
    <div v-else class="text-center text-gray-500 py-20">
      Пост не найден.
    </div>
    <div class="mt-10">
      <h2 v-if="comments.value != null" class="text-2xl font-bold text-gray-800 mb-4">Комментарии</h2>
      <div class="space-y-6">
        <!-- Один комментарий -->
        <div class="bg-gray-50 rounded-lg p-4 shadow flex flex-col" v-for="comment in comments" :key="comment.id">
          <div class="flex items-center mb-2">
            <span class="font-semibold text-gray-700 mr-2">{{ comment.user.name }}</span>
            <span class="text-xs text-gray-400">{{ formatDate(comment.created_at) }}</span>
          </div>
          <div class="text-gray-800">
            Текст комментария. Здесь будет содержимое комментария пользователя.
          </div>
        </div>
        <!-- /Один комментарий -->
      </div>
    </div>
  </div>


</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const post = ref(null)
const comments = ref([])
const loading = ref(true)
const error = ref('')

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(async () => {
  loading.value = true
  error.value = ''

  console.log("Route: ", route.params);


  try {
    const { data: recivedPost } = await axios.get(`/api/posts/${route.params.id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('auth_token')}`
      }
    })

    const { data: recivedComments } = await axios.get(`/api/posts/${route.params.id}/comments`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('auth_token')}`
      }
    })

    post.value = recivedPost.post;
    comments.value = recivedComments;
    console.log("Post: ", post.value);
    console.log("Comments: ", comments.value);
  } catch (e) {
    error.value = e.response?.data?.message || 'Ошибка загрузки поста'
  } finally {
    loading.value = false
  }
})

const dislikePost = async () => {
  const response = await axios.post(`/api/posts/${post.value.id}/dislike`, {}, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  })

  console.log(response.data.likes);

  post.value.likes = response.data.likes
}

const likePost = async () => {
  const response = await axios.post(`/api/posts/${post.value.id}/like`, {}, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('auth_token')}`
    }
  })

  console.log(response.data.likes);

  post.value.likes = response.data.likes
}
</script>