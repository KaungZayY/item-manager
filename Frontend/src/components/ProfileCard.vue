<script setup>
import axios from 'axios';
import { reactive, onMounted } from 'vue';
import router from '@/router';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const state = reactive({
    data: [],
    isLoading: true
});

onMounted(async () => {
    try {
        const response = await axios.get('/api/profile');
        state.data = response.data;
    }
    catch (error) {
        if (error.response && error.response.status === 401) {
            toast.error('Login First!');
            router.push('/login');
        };
        console.error('Error fetching data', error);
    }
    finally {
        state.isLoading = false;
    }
})
</script>

<template>
    <div v-if="state.isLoading" class="text-center text-gray-500 py-6">
        <PulseLoader />
    </div>
    <div v-else
        class="max-w-7xl mx-auto mt-12 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">User Profile</h2>
        <div class="space-y-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Name</label>
                <input type="text" id="name" readonly :value="state.data.user.name"
                    class="block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm bg-gray-100 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Email</label>
                <input type="email" id="email" readonly :value="state.data.user.email"
                    class="block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm bg-gray-100 text-gray-900 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
    </div>
</template>