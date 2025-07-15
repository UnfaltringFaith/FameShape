<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6">
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center">
            <img :src="`/storage/${muscles_groups.image}`" alt="Тестовое изображение"
                class="h-24 w-24 object-cover rounded-full mb-4">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Тестовая группа</h3>
            <p class="text-gray-600 text-sm text-center mb-2">Описание тестовой группы мышц.</p>
            <span class="inline-block bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full mt-2">test-slug</span>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
const muscles_groups = ref([]);

onMounted(() => {
    // Additional setup logic can go here


    // Fetch muscle groups from the API
    axios.get('/api/knowledge_base/muscle_groups')
        .then(response => {
            muscles_groups.value = response.data[6];
            console.log('MuscleGroups component mounted', );

        })
        .catch(error => {
            console.error('Error fetching muscle groups:', error);
        });
});

</script>