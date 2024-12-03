<script setup>
const emit = defineEmits(['show_all']);

const showAll = () => {
    emit('show_all');
};
</script>

<template>
    <div class="w-full sm:w-auto">
        <button @click="showAll"
        class="w-full mt-5 sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100 dark:focus:ring-offset-gray-800 dark:bg-indigo-500 dark:hover:bg-indigo-600">
            Show All
        </button>
    </div>
</template>