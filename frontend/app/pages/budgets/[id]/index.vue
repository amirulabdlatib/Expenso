<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Loading State -->
            <div v-if="status === 'pending'" class="flex items-center justify-center py-20">
                <div class="text-center">
                    <div class="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-gray-600">Loading transactions...</p>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-white rounded-lg shadow-sm border border-red-200 p-12 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl text-red-600">⚠</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to load transactions</h3>
                <p class="text-gray-600 mb-6">{{ error.message || "An error occurred while fetching transactions" }}</p>
                <button class="inline-flex items-center justify-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors" @click="refresh()">
                    <span>Try Again</span>
                </button>
            </div>

            <!-- Success State -->
            <template v-else>
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <NuxtLink to="/budgets" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="text-gray-600">←</span>
                            </NuxtLink>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Budget Transactions</h1>
                                <p class="text-gray-600 mt-2">{{ transactions.length }} transactions</p>
                            </div>
                        </div>
                        <button :disabled="status === 'pending'" class="flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50" @click="refresh()">
                            <span :class="[status === 'pending' ? 'animate-spin' : '', 'text-xl text-gray-600']">↻</span>
                        </button>
                    </div>
                </div>

                <!-- Transactions List -->
                <div v-if="transactions.length > 0" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Table Header -->
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                        <div class="grid grid-cols-12 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="col-span-1">Date</div>
                            <div class="col-span-3">Name</div>
                            <div class="col-span-2">Category</div>
                            <div class="col-span-2">Account</div>
                            <div class="col-span-2 text-right">Amount</div>
                        </div>
                    </div>

                    <!-- Table Body -->
                    <div class="divide-y divide-gray-200">
                        <div v-for="transaction in transactions" :key="transaction.id" class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Date -->
                                <div class="col-span-1">
                                    <p class="text-sm text-gray-900 font-medium">{{ formatDate(transaction.transaction_date) }}</p>
                                    <p class="text-xs text-gray-500">{{ formatTime(transaction.transaction_date) }}</p>
                                </div>

                                <!-- Name -->
                                <div class="col-span-3">
                                    <p class="text-sm font-medium text-gray-900">{{ transaction.name }}</p>
                                </div>

                                <!-- Category -->
                                <div class="col-span-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ transaction.category }}
                                    </span>
                                </div>

                                <!-- Account -->
                                <div class="col-span-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ transaction.account }}
                                    </span>
                                </div>

                                <!-- Amount -->
                                <div class="col-span-2 text-right">
                                    <p v-if="transaction.debit > 0" class="text-sm font-semibold text-red-600">-{{ formatCurrency(transaction.debit) }}</p>
                                    <p v-else class="text-sm font-semibold text-green-600">+{{ formatCurrency(transaction.credit) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl text-gray-400">📋</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No transactions found</h3>
                    <p class="text-gray-600">There are no transactions for this budget period</p>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const route = useRoute();
    const { formatCurrency } = useCurrency();
    const { formatDate, formatTime } = useUtils();

    const { data, status, error, refresh } = await useSanctumFetch(`/api/budgets/${route.params.id}`);

    const transactions = computed(() => {
        return data.value?.transactions || [];
    });
</script>
