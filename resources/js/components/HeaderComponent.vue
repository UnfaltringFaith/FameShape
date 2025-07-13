<template>
  <header class="w-full bg-white shadow-md py-4 px-8 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <!--<img  alt="Logo" class="h-8 w-8" />-->
      <router-link to="/" class="text-xl font-bold text-gray-800">FameShape</router-link>
    </div>
    <nav class="flex gap-6">
      <router-link to="/" class="text-gray-700 hover:text-blue-600 font-medium transition">Главная</router-link>
      <router-link to="/posts" class="text-gray-700 hover:text-blue-600 font-medium transition">Блог</router-link>
      <router-link to="/knowledge_base/muscle_groups" class="text-gray-700 hover:text-blue-600 font-medium transition">База знаний</router-link>
      <router-link to="/dashboard" class="text-gray-700 hover:text-blue-600 font-medium transition">Тренировки</router-link>
      <router-link :to="username ? '/user' : '/login'" class="text-gray-700 hover:text-blue-600 font-medium transition"> {{ username ? username : 'Войти' }}</router-link>
    </nav>
  </header>
</template>

<script setup>
import { onMounted, ref } from 'vue';

const token = localStorage.getItem('auth_token') || null;
const username = ref(null);

onMounted(() => {
  if (token) {
    axios.get('/api/user', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).then(response => {
      // User is authenticated
      console.log('User data:', response);
      username.value = response.data.user.name; // Assuming the user object has a name property
    }).catch(() => {
      // Token is invalid or expired
      console.log('User not authenticated');re
    });
  } else {
    // User is not authenticated
  }
})

</script>

<style scoped>
header {
  z-index: 10;
}
</style>
