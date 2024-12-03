<script setup>
const emit = defineEmits(['from_date', 'to_date']);

const from_date = (e) => {
    emit('from_date', e.target.value)
}

const to_date = (e) => {
    emit('to_date', e.target.value)
}
</script>

<template>
    <div class="w-full sm:w-auto">
        <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            From Date
        </label>
        <input @input="from_date" type="date" name="from_date" id="from_date"
            class="px-4 py-2 block w-full sm:w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <!-- To Date Filter -->
    <div class="w-full sm:w-auto">
        <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            To Date
        </label>
        <input @input="to_date" type="date" name="to_date" id="to_date"
            class="px-4 py-2 block w-full sm:w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>
</template>