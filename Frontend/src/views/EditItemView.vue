<script setup>
import AuthNavBar from '@/components/AuthNavBar.vue';
import Footer from '@/components/Footer.vue'
import ItemEditForm from '@/components/ItemEditForm.vue';
import Wrapper from '@/components/Wrapper.vue';

import { reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useToast } from 'vue-toastification';
import router from '@/router';
import axios from 'axios';

const route = useRoute();
const itemId = route.params.id;
const toast = useToast();

const state = reactive({
    item: {},
    isLoading: true
});

onMounted(async () => {
    try {
        const response = await axios.get(`/api/items/${itemId}`);
        state.item = response.data.item;
    } catch (error) {
        if (error.response && error.response.status === 404) {
            toast.error('Item Not Found!');
        };
        if (error.response && error.response.status === 401) {
            toast.error('Login First!');
            router.push('/login');
        };
        console.error('Error fetching job', error);
    } finally {
        state.isLoading = false;
    }
});
</script>

<template>
  <Wrapper>
    <AuthNavBar />
    <PulseLoader v-if="state.isLoading" />
    <ItemEditForm v-else :item="state.item"/>
  </Wrapper>
  <Footer />
</template>