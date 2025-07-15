<template>
    <div class="max-w-2xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Создать новый пост</h1>
        <form @submit.prevent="handleSubmit" class="space-y-6 bg-white p-8 rounded-xl shadow-md">
            <!-- Заголовок -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="title">Заголовок</label>
                <input v-model="form.title" id="title" type="text" required
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Введите заголовок поста" />
            </div>
            <!-- Содержимое -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="content">Содержимое</label>
                <textarea v-model="form.content" id="content" rows="6" required
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Введите текст поста"></textarea>
            </div>
            <!-- Теги -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="tags">Теги (через запятую)</label>
                <input v-model="form.tags" id="tags" type="text" @focus="showTags = true" @blur="hideTags"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="например: vue, laravel, api" />
                <ul v-if="showTags" class="absolute bg-white border border-gray-300 rounded-lg mt-1 w-full z-10">
                    <li v-for="tag in tags" :key="tag.id" @mousedown.prevent="selectTag(tag)"
                        class="px-4 py-2 hover:bg-indigo-100 cursor-pointer">{{ tag.name }}</li>

                </ul>

                <div class="flex flex-wrap">
                    <div v-for="tag in selectedTags"
                        class=" flex bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full mr-2 mt-2">
                        <p>{{ tag.name }}</p>
                        <button @click="removeTag(tag)"
                            class="ml-1 text-indigo-600 hover:text-indigo-900">&times;</button>
                    </div>
                </div>

            </div>
            <!-- Картинка -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="image">Изображение</label>
                <input @change="onFileChange" id="image" type="file" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                <div v-if="preview" class="mt-2">
                    <img :src="preview" alt="Превью" class="h-32 rounded-lg object-cover" />
                </div>
            </div>
            <!-- Опубликовано -->
            <div class="flex items-center">
                <input v-model="form.is_published" id="is_published" type="checkbox"
                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                <label for="is_published" class="ml-2 block text-sm text-gray-700">
                    Опубликовать сразу
                </label>
            </div>
            <!-- Ошибка -->
            <div v-if="error" class="text-red-600 text-sm">{{ error }}</div>
            <!-- Кнопка -->
            <div>
                <button type="submit" :disabled="loading"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 font-medium text-lg transition disabled:opacity-50">
                    {{ loading ? 'Сохранение...' : 'Создать пост' }}
                </button>
            </div>
        </form>
        <div
  v-if="notification"
  class="fixed bottom-6 right-6 z-50 bg-green-100 text-green-800 px-6 py-3 rounded-lg shadow-lg font-semibold transition">
  {{ notification }}
</div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const tags = ref([])
const showTags = ref(false)
const selectedTags = ref([])
const loading = ref(false)
const notification = ref('')
const router = useRouter()

const form = ref({
    title: '',
    content: '',
    tags: '',
    image: null,
    is_published: false
})

const handleSubmit = async () => {
    let selectedIds = selectedTags.value.map(tag => tag.id);

    loading.value = true
    
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('content', form.value.content)
    selectedIds.forEach(id => formData.append('tags[]', id))
    if (form.value.image) {
        formData.append('image', form.value.image)
    }
    formData.append('is_published', form.value.is_published ? 1 : 0)

    try {
        const response = await axios.post('/api/posts', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
            }
        })

        notification.value = 'Пост успешно создан!'
        setTimeout(() => {
            notification.value = ''
        }, 3000)
        console.log('Form submitted, post created', response.data);

        router.push(`/posts/${response.data.post.id}`) // Перенаправление на страницу постов
    } catch (error) {
        console.error('Error during form submission:', error)
    } finally {
        loading.value = false
        form.value = {
            title: '',
            content: '',
            tags: '',
            image: null,
            is_published: false
        }
        selectedTags.value = []
        showTags.value = false
    }
}

const selectTag = (tag) => {
    if (!selectedTags.value.includes(tag)) {
        selectedTags.value.push(tag)
    }

    console.log('Tag selected:', tag)

    tags.value = tags.value.filter(t => t.id !== tag.id)
}

const loadTags = async () => {
    const response = await axios.get('/api/tags')
    tags.value = response.data

}

const hideTags = () => {
    setTimeout(() => {
        showTags.value = false
    }, 200)
}

const removeTag = (tag) => {
    selectedTags.value = selectedTags.value.filter(t => t.id !== tag.id)
    tags.value.push(tag) // Возвращаем тег в список доступных
}

const onFileChange = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.value.image = file
        preview.value = URL.createObjectURL(file)
    } else {
        form.value.image = null
        preview.value = null
    }
}

onMounted(() => {
    loadTags()
})

</script>