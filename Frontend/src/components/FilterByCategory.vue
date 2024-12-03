<script setup>
import { reactive, onMounted } from 'vue';
import axios from 'axios';

const state = reactive({
    categories: [],
    isLoading: true
});

onMounted(async () => {
    const response = await axios.get('/api/categories');
    state.categories = response.data.categories;
    state.isLoading = false;
});

const emit = defineEmits(['category_filter']);

const category_filter = (e) => {
    emit('category_filter', e.target.value)
}
</script>

<template>
    <div class="w-full sm:w-auto">
        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Category
        </label>
        <select @change="category_filter" name="category_id" id="category_id" :disabled="state.isLoading"
            class="px-4 py-2 block w-full sm:w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="" default>Category</option>
            <option v-for="category in state.categories" :key="category.id" :value="category.id">
                {{ category.category }}
            </option>
        </select>
    </div>
</template>