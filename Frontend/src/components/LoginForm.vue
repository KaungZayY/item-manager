<script setup>
import { RouterLink } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { reactive } from 'vue';
import router from '@/router';
import VueCookies from 'vue-cookies';

const form = reactive({
    email: '',
    password: ''
});

const errors = reactive({
    email: '',
    password: '',
});

const toast = useToast();

const handleLogin = async () => {
    const credentials = {
        email: form.email,
        password: form.password,
    };

    try {
        const response = await axios.post(`api/login`, credentials);
        VueCookies.set('token', response.data.access_token, {
            expires: response.data.expires_in / 60 / 24,
            secure: true,
            sameSite: 'Strict',
        });
        toast.success('Login Successful!');
        router.push('/');
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
        } else if (error.response && error.response.status === 401) {
            toast.error('Invalid email or password. Please try again.');
        } else {
            toast.error('An error occurred while logging in. Please try again.');
            console.error('Error While Login:', error);
        }
    }
};
</script>

<template>
    <section class="bg-gray-50 dark:bg-gray-900">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                <div
                    class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <h1
                            class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                            Sign in to your account
                        </h1>
                        <form @submit.prevent="handleLogin" class="space-y-4 md:space-y-6">
                            <div>
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                    email</label>
                                <input v-model="form.email" type="email" name="email" id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="name@company.com">
                                <div v-if="errors.email" class="text-red-500 text-sm mt-2">{{ errors.email[0] }}</div>
                            </div>
                            <div>
                                <label for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <input v-model="form.password" type="password" name="password" id="password" placeholder="••••••••"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                <div v-if="errors.password" class="text-red-500 text-sm mt-2">{{ errors.password[0] }}</div>
                            </div>
                            <button type="submit"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Sign in
                            </button>

                            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                                Don’t have an account yet? <RouterLink to="register"
                                    class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign
                                    up</RouterLink>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
</template>