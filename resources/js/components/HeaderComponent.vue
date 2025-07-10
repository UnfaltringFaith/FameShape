<template>
  <header class="w-full bg-white shadow-md py-4 px-8 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <!--<img  alt="Logo" class="h-8 w-8" />-->
      <span class="text-xl font-bold text-gray-800">FameShape</span>
    </div>
    <nav class="flex gap-6">
      <a href="/" class="text-gray-700 hover:text-blue-600 font-medium transition">Главная</a>
      <a href="/dashboard" class="text-gray-700 hover:text-blue-600 font-medium transition">Блог</a>
      <a href="/dashboard" class="text-gray-700 hover:text-blue-600 font-medium transition">Тренировки</a>
      <a href="/login" class="text-gray-700 hover:text-blue-600 font-medium transition">Войти</a>
    </nav>
  </header>
</template>

<script setup>
// Можно добавить логику авторизации или меню позже
</script>

<style scoped>
header {
  z-index: 10;
}
</style>
