<script setup>
import Footer from '@/components/Footer.vue'
import Wrapper from '@/components/Wrapper.vue';
import AuthNavBar from '@/components/AuthNavBar.vue';
import ItemCard from '@/components/ItemCard.vue';
import FilterWrapper from '@/components/FilterWrapper.vue';

import axios from 'axios';
import { reactive, onMounted, computed } from 'vue';
import router from '@/router';
import { useToast } from 'vue-toastification';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';
import { RouterLink } from 'vue-router';

import FilterByTitle from '@/components/FilterByTitle.vue';
import FilterByCategory from '@/components/FilterByCategory.vue';
import FilterByDate from '@/components/FilterByDate.vue';
import ClearFilter from '@/components/ClearFilter.vue';

const toast = useToast();

const state = reactive({
    items: [],
    isLoading: true
});

const searchFilter = reactive({
    title: '',
});

const dateFilter = reactive({
    from_date: '',
    to_date: '',
});

const dropListFilter = reactive({
    category: '',
})

const filteredItems = computed(() => {
    let items = state.items;
    if(searchFilter.title !== ''){
        items = items.filter(item => item.title.includes(searchFilter.title));
    }

    if(dateFilter.from_date !== '' && dateFilter.to_date !== ''){
        const fromDate = new Date(dateFilter.from_date);
        const toDate = new Date(dateFilter.to_date);
        items = items.filter(item => {
            const itemDate = new Date(item.created_date);
            return itemDate >= fromDate && itemDate <= toDate;
        });
    }

    if(dropListFilter.category !== ''){
        items = items.filter(item => item.category_id == dropListFilter.category);
    }

    return items;
})

const handleSearch = (search) => {
    searchFilter.title = search;
}

const handleFromDate = (fromDate) => {
    dateFilter.from_date = fromDate;
};

const handleToDate = (toDate) => {
    dateFilter.to_date = toDate;
};

const handleCategory = (category) => {
    dropListFilter.category = category;
}

const remoteAllFilterValues = () => {
    searchFilter.title = '';
    dateFilter.from_date = '';
    dateFilter.to_date = '';
    dropListFilter.category = '';
};

const handleDelete = (itemId) => {
    state.items = state.items.filter(item => item.id !== itemId);
};

onMounted(async () => {
    try {
        const response = await axios.get('/api/items');
        state.items = response.data.items;
        // console.log(state.items)
    }
    catch (error) {
        if (error.response && error.response.status === 401) {
            toast.error('Login First!');
            router.push('/login');
        };
        // console.error('Error fetching data', error);
    }
    finally {
        state.isLoading = false;
    }
})
</script>

<template>
    <Wrapper>
        <AuthNavBar />
        <FilterWrapper>
            <FilterByCategory @category_filter="handleCategory" />
            <FilterByTitle @search="handleSearch" />
            <FilterByDate @from_date="handleFromDate" @to_date="handleToDate"/>
            <ClearFilter @show_all="remoteAllFilterValues"/>
        </FilterWrapper>
        <h1 class="text-black dark:text-white text-2xl text-center pt-2">Items</h1>
        <div class="flex flex-row justify-end">
            <RouterLink to="/item/create"
                class="items-center mr-2 px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Add Item
            </RouterLink>
        </div>
        <div v-if="state.isLoading" class="text-center text-gray-500 py-6">
            <PulseLoader />
        </div>
        <div v-else class="flex flex-col md:flex-row flex-wrap pt-2 pb-8">
            <ItemCard v-for="item in filteredItems" :key="item.id" :item="item" @delete_item="handleDelete"/>
        </div>
    </Wrapper>
    <Footer />
</template>