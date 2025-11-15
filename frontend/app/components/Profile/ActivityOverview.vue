<template>
    <!-- Activity Overview -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Activity Overview</h2>
            <select v-model="activityPeriod" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <Icon name="heroicons:document-text" class="w-8 h-8 text-indigo-600 mx-auto mb-2" />
                <p class="text-2xl font-bold text-gray-900">{{ totalTransactions }}</p>
                <p class="text-xs text-gray-600">Transactions</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <Icon name="heroicons:building-library" class="w-8 h-8 text-blue-600 mx-auto mb-2" />
                <p class="text-2xl font-bold text-gray-900">{{ totalAccounts }}</p>
                <p class="text-xs text-gray-600">Accounts</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    const activityPeriod = ref("month");

    defineProps({
        totalAccounts: {
            type: Number,
            default: 0,
        },
        totalTransactions: {
            type: Number,
            default: 0,
        },
    });
</script>
