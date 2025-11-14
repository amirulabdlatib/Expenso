<template>
    <!-- Stats Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Financial Stats</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <Icon name="heroicons:arrow-trending-up" class="w-5 h-5 text-green-600" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Income</p>
                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(totalIncome) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <Icon name="heroicons:arrow-trending-down" class="w-5 h-5 text-red-600" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Expenses</p>
                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(totalExpenses) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <Icon name="heroicons:wallet" class="w-5 h-5 text-indigo-600" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Net Balance</p>
                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(totalNetBalance) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <Icon name="heroicons:chart-pie" class="w-5 h-5 text-purple-600" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Savings Rate</p>
                        <p class="text-lg font-bold text-gray-900">25%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    const { formatCurrency } = useCurrency();

    defineProps({
        totalIncome: {
            type: Number,
            default: 0,
        },
        totalExpenses: {
            type: Number,
            default: 0,
        },
        totalNetBalance: {
            type: Number,
            default: 0,
        },
    });
</script>
