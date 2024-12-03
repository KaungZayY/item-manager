<script setup>
import axios from 'axios';
import { useToast } from 'vue-toastification';
import router from '@/router';
import VueCookies from 'vue-cookies';

const toast = useToast();

const handleLogout = async () => {
    try {
        await axios.post(`api/logout`);
        VueCookies.remove('token');
        toast.success('Logout Success!');
    } catch (error) {
        if (error.response && error.response.status === 401) {
            toast.error('Login First!');
        };
    } finally {
        router.push('/login');
    }
    
};
</script>

<template>
    <button @click="handleLogout"
        class="block py-2 px-3 text-gray-900 bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-gray-200 md:dark:text-blue-400 dark:bg-transparent"
        aria-current="page">Logout</button>
</template>