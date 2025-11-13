<template>
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Recent Transactions</h2>
            <NuxtLink to="/transactions" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"> View All → </NuxtLink>
        </div>

        <div v-if="recentTransactions.length > 0" class="space-y-4">
            <!-- Transaction Item -->
            <div v-for="transaction in recentTransactions" :key="transaction.id" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-2xl" :class="getColorClasses(transaction.category.color).bgClass">
                        <Icon :name="transaction.category.icon" class="w-6 h-6" :class="getColorClasses(transaction.category.color).textClass" />
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ transaction.name }}</p>
                        <p class="text-sm text-gray-500">{{ transaction.category.name }} • {{ formatRelativeDate(transaction.transaction_date) }}</p>
                    </div>
                </div>
                <p v-if="transaction.credit" class="text-lg font-semibold text-green-600">RM {{ transaction.credit }}</p>
                <p v-else class="text-lg font-semibold text-red-600">RM {{ transaction.debit }}</p>
            </div>
        </div>

        <div v-else class="text-center py-8 px-4">
            <div class="mx-auto w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                <Icon name="heroicons:document-text" class="w-8 h-8 text-blue-500" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No transactions yet</h3>
            <p class="text-gray-500 mb-6">Start tracking your expenses and income to see them here</p>
            <NuxtLink
                to="/transactions/create"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <Icon name="heroicons:plus" class="-ml-1 mr-2 h-5 w-5" />
                Add Transaction
            </NuxtLink>
        </div>
    </div>
</template>

<script setup>
    const { formatRelativeDate } = useUtils();
    const { getColorClasses } = useCategoryConstant();

    defineProps({
        recentTransactions: {
            type: Array,
            default: () => [],
        },
    });
</script>
