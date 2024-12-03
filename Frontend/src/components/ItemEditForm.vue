<script setup>
import { defineProps, reactive, onMounted } from "vue";
import { useToast } from 'vue-toastification';
import axios from 'axios';
import router from '@/router';

const props = defineProps({
    item: Object,
});

const form = reactive({
    title: '',
    category_id: '',
    description: '',
});

const state = reactive({
    categories: [],
    isLoading: true
});

const errors = reactive({
    title: '',
    category_id: '',
    description: '',
});

const toast = useToast();

const handleUpdate = async () => {
    const updatedItem = {
        title: form.title,
        category_id: form.category_id,
        description: form.description,
    };

    try {
        await axios.post(`/api/items/${props.item.id}`, updatedItem);
        toast.success('Item Updated Successfully');
        router.push(`/`);
    } catch (error) {
        if (error.response && error.response.status === 422) {
            Object.keys(errors).forEach((key) => {
                errors[key] = '';
            });

            const validationErrors = error.response.data.errors;
            for (const key in validationErrors) {
                if (validationErrors.hasOwnProperty(key)) {
                    errors[key] = validationErrors[key];
                }
            }
        } 
        else if(error.response && error.response.status === 401){
            toast.error('Login First!');
            router.push('/login');
        }
        else {
            toast.error('An error occurred while Updating an Item. Please try again later.');
            console.error('Error While Update:', error);
        }
    }
};

onMounted(async () => {
    const response = await axios.get('/api/categories');
    state.categories = response.data.categories;
    state.isLoading = false;

    form.title = props.item.title;
    form.category_id = props.item.category_id;
    form.description = props.item.description;
});
</script>

<template>
    <form @submit.prevent="handleUpdate" class="min-w-80 mx-auto pt-8">
        <h1 class="text-black dark:text-white text-2xl text-center mb-4">Update Item</h1>
        <div class="relative z-0 w-full mb-8 group">
            <input v-model="form.title" type="text" name="title" id="title"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" " />
            <div v-if="errors.title" class="text-red-500 text-sm mt-2">{{ errors.title[0] }}</div>
            <label for="title"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Title</label>
        </div>
        <div class="relative z-0 w-full mb-8 group text-black dark:text-white">
            <select v-model="form.category_id" name="category_id" id="category_id"
                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                >
                <option value="" class="text-gray-900 dark:text-gray-500">-- SELECT ONE --</option>
                <option v-for="category in state.categories" :key="category.id" :value="category.id" class="text-gray-900 dark:text-gray-500">
                    {{ category.category }}
                </option>
            </select>
            <label for="category_id"
            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
            Category</label>
            <div v-if="errors.category_id" class="text-red-500 text-sm mt-2">{{ errors.category_id[0] }}</div>
        </div>
        <div class="relative z-0 w-full mb-8 group">
            <textarea v-model="form.description" type="text" name="description" id="description" rows="3"
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "></textarea>
            <label for="description"
                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                Description</label>
            <div v-if="errors.description" class="text-red-500 text-sm mt-2">{{ errors.description[0] }}</div>
        </div>
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
    </form>
</template>