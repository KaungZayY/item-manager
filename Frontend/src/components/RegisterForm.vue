<script setup>
import { RouterLink } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { reactive } from 'vue';
import router from '@/router';
import VueCookies from 'vue-cookies';

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const errors = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const toast = useToast();

const handleRegister = async () => {
    const newUser = {
        name: form.name,
        email: form.email,
        password: form.password,
        password_confirmation: form.password_confirmation,
    };

    try {
        const response = await axios.post(`api/register`, newUser);
        VueCookies.set('token', response.data.access_token, {
            expires: response.data.expires_in / 60 / 24,
            secure: true,
            sameSite: 'Strict',
        });
        toast.success('Created an Account!');
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
        }  else {
            toast.error('An error occurred while creating an account. Please try again later.');
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
                            Create an account
                        </h1>
                        <form @submit.prevent="handleRegister" class="space-y-4 md:space-y-6" action="#">
                            <div>
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                    name</label>
                                <input v-model="form.name" type="text" name="name" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="John Doe">
                                <div v-if="errors.name" class="text-red-500 text-sm mt-2">{{ errors.name[0] }}</div>
                            </div>
                            <div>
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                                    email</label>
                                <input v-model="form.email" type="email" name="email" id="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="name@company.com">
                                <div v-if="errors.email" class="text-red-500 text-sm mt-2">{{ errors.email[0] }}</div>
                            </div>
                            <div>
                                <label for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                <input v-model="form.password" type="password" name="password" id="password" placeholder="••••••••"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   >
                                <div v-if="errors.password" class="text-red-500 text-sm mt-2">{{ errors.password[0] }}</div>
                            </div>
                            <div>
                                <label for="confirm-password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                                    password</label>
                                <input v-model="form.password_confirmation" type="password" name="confirm-password" id="confirm-password"
                                    placeholder="••••••••"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   >
                                <div v-if="errors.password_confirmation" class="text-red-500 text-sm mt-2">{{ errors.password_confirmation[0] }}</div>
                            </div>
                            <button type="submit"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Create
                                an account</button>
                            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                                Already have an account? <RouterLink to="login"
                                    class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login
                                    here</RouterLink>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
</template>