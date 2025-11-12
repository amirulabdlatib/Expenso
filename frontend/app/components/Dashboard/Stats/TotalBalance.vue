<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                    <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-600 mb-1">Total Balance</p>
        <p class="text-2xl font-bold text-gray-900">RM {{ totalBalance }}</p>
        <p class="text-sm text-green-600 mt-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m5 12 7-7 7 7" />
                <path d="M12 19V5" />
            </svg>
            +12.5% from last month
        </p>
    </div>
</template>

<script setup>
    defineProps({
        totalBalance: {
            type: Number,
            default: 0,
        },
    });
</script>
