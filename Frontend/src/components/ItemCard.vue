<script setup>
import { defineProps, reactive } from "vue";
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { RouterLink } from 'vue-router';

const props = defineProps({
    item: Object,
});

const toast = useToast();

const emit = defineEmits(['delete_item']);

const deleteItem = async () => {
  try {
    const confirm = window.confirm('Are you sure you want to delete this item?');
    if (confirm) {
      await axios.delete(`/api/items/${props.item.id}`);
      toast.success('Item has Successfully Removed!');
      emit('delete_item', props.item.id);
    }
  } catch (error) {
    console.error('Error deleting item', error);
    toast.error('Item cannot be Removed');
  }
}

const menu = reactive({
    isOpen: false,
});

const toggleMenu = () => {
    menu.isOpen = !menu.isOpen;
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', { year: 'numeric', month: 'long', day: 'numeric' }).format(date);
};

</script>

<template>
    <div
        class="w-full h-auto relative sm:w-1/2 md:w-1/3 lg:w-1/4 flex flex-col px-2 py-2 text-black transition duration-300 ease-in-out transform hover:scale-105">
        <div
            class="rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 px-4 py-4 shadow-lg">
            <div class="flex flex-row justify-between items-center mt-2">
                <p class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline">
                    {{ item.title }}
                </p>
                <button @click="toggleMenu" class="focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="4" viewBox="0 0 128 512"
                        class="fill-gray-500 dark:fill-gray-400">
                        <path
                            d="M64 360a56 56 0 1 0 0 112 56 56 0 1 0 0-112zm0-160a56 56 0 1 0 0 112 56 56 0 1 0 0-112zM120 96A56 56 0 1 0 8 96a56 56 0 1 0 112 0z" />
                    </svg>
                </button>
            </div>
            <!-- Buttons (Edit/Delete) -->
            <div v-if="menu.isOpen" class="flex flex-col absolute inset-0 z-20 right-6 top-14 items-end">
                <RouterLink :to="`/item/edit/${item.id}`"
                    class="bg-blue-500 hover:bg-blue-700 text-center text-white px-3 py-1 mb-1 rounded-md w-20 shadow-md transition ease-in-out duration-150">
                    Edit
                </RouterLink>
                <button @click="deleteItem"
                    class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 mb-1 rounded-md w-20 shadow-md transition ease-in-out duration-150">
                    Delete
                </button>
            </div>
            <!-- Description -->
            <p class="text-sm text-gray-700 dark:text-gray-400 mt-4 h-20 overflow-auto">
                {{ item.description }}
            </p>
            <!-- Metadata -->
            <p class="text-xs text-gray-600 dark:text-gray-500 mt-3">
                <span class="font-medium">Created at:</span> {{ formatDate(item.created_date) }}
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-500 mt-1 mb-1">
                <span class="font-medium">Category:</span> {{ item.category.category }}
            </p>
        </div>
    </div>
</template>