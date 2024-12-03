<script setup>

const emit = defineEmits(['search']);

const search = (e) => {
    emit('search', e.target.value)
}
</script>

<template>
    <div class="w-full sm:w-auto">
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Title
        </label>
        <input @input="search" type="text" name="title" id="title" placeholder="Title"
            class="px-4 py-2 block w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>
</template>