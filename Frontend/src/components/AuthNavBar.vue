<script setup>
import { RouterLink } from 'vue-router';
import LogoutBtn from './LogoutBtn.vue';

</script>

<template>
    <nav class="bg-white dark:bg-gray-900 border-gray-200">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-end mx-auto p-4">
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul
                        class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-200 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li>
                            <RouterLink to="/"
                                class="block py-2 px-3 text-gray-900 bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-gray-200 md:dark:text-blue-400 dark:bg-transparent"
                                aria-current="page">Home</RouterLink>
                        </li>
                        <li>
                            <RouterLink to="profile"
                                class="block py-2 px-3 text-gray-900 bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-gray-200 md:dark:text-blue-400 dark:bg-transparent"
                                aria-current="page">Profile</RouterLink>
                        </li>
                        <li>
                            <LogoutBtn />
                        </li>
                    </ul>
                </div>
            </div>
        </nav> 
</template>