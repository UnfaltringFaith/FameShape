<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Заголовок страницы -->
     <div class="flex items-center mb-8">

       <div class="flex-1 text-center mb-6">
         <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Все посты</h1>
         <p class="text-xl text-gray-600">Читайте последние новости и статьи</p>
        </div>
        <router-link to="/posts/create" class="whitespace-nowrap inline-block bg-blue-500 text-white py-2 px-4 rounded-lg ml-auto">Создать пост</router-link>
      </div>

    <!-- Загрузка -->
    <div v-if="loading" class="flex flex-col items-center justify-center py-16">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
      <p class="text-gray-600">Загрузка постов...</p>
    </div>

    <!-- Список постов -->
    <div v-else-if="posts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
      <article 
        v-for="post in posts" 
        :key="post.id" 
        class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
      >
        <!-- Изображение поста -->
        <div class="h-48 overflow-hidden" v-if="post.image">
          <img 
            :src="`/storage/${post.image}`" 
            :alt="post.title"
            class="w-full h-full object-cover"
          />
        </div>
        <div v-else class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
          <div class="text-5xl text-white">📝</div>
        </div>

        <!-- Содержимое поста -->
        <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">{{ post.title }}</h2>
          <p class="text-gray-600 mb-4 line-clamp-3">{{ truncatedContent(post.content) }}</p>
          
          <!-- Метаинформация -->
          <div class="flex justify-between items-center mb-4 text-sm text-gray-500">
            <div class="flex items-center space-x-2" v-if="post.user">
              <span class="text-lg">👤</span>
              <span>{{ post.user.name }}</span>
            </div>
            <div class="flex items-center space-x-4">
              <span class="flex items-center space-x-1">
                <span>👁</span>
                <span>{{ post.views || 0 }}</span>
              </span>
              <span class="flex items-center space-x-1">
                <span>❤️</span>
                <span>{{ post.likes || 0 }}</span>
              </span>
            </div>
          </div>

          <!-- Теги -->
          <div class="mb-4" v-if="post.tags && post.tags.length > 0">
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="tag in post.tags" 
                :key="tag.id"
                class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full"
              >
                #{{ tag.name }}
              </span>
            </div>
          </div>

          <!-- Кнопка "Читать далее" -->
          <router-link 
            :to="`/posts/${post.id}`"
            class="inline-block w-full text-center bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105"
          >
            Читать далее
          </router-link>
        </div>
      </article>
    </div>

    <!-- Пустое состояние -->
    <div v-else class="text-center py-20">
      <div class="text-6xl mb-6">📰</div>
      <h2 class="text-2xl font-semibold text-gray-900 mb-2">Постов пока нет</h2>
      <p class="text-gray-600">Скоро здесь появятся интересные статьи!</p>
    </div>

    <!-- Пагинация -->
    <div v-if="pagination.last_page > 1" class="flex justify-center items-center space-x-4 mt-12">
      <button 
        @click="loadPage(pagination.current_page - 1)"
        :disabled="pagination.current_page <= 1"
        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
      >
        ← Предыдущая
      </button>
      
      <span class="text-gray-600 font-medium">
        Страница {{ pagination.current_page }} из {{ pagination.last_page }}
      </span>
      
      <button 
        @click="loadPage(pagination.current_page + 1)"
        :disabled="pagination.current_page >= pagination.last_page"
        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
      >
        Следующая →
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Реактивные данные
const posts = ref([])
const loading = ref(true)
const pagination = ref({})
const counter = ref(0)


// Загрузка постов
const loadPosts = async (page = 1) => {
  try {
    loading.value = true
    const response = await axios.get(`/api/posts?page=${page}`,{
      headers:{
        Authorization: `Bearer ${localStorage.getItem('auth_token')}`
      }
    })

    console.log(response);
    
    
    if (response.data.data) {
      // Если есть пагинация
      posts.value = response.data.data
      pagination.value = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        total: response.data.total
      }
    } else {
      // Если массив без пагинации
      posts.value = response.data
    }
  } catch (error) {
    console.error('Ошибка загрузки постов:', error)
    posts.value = []
  } finally {
    loading.value = false
  }
}

// Загрузка конкретной страницы
const loadPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    loadPosts(page)
  }
}

// Обрезка текста
const truncatedContent = (content) => {
  if (!content) return ''
  return content.length > 150 ? content.substring(0, 150) + '...' : content
}

// Загрузка при монтировании
onMounted(() => {
  loadPosts()
})
</script>