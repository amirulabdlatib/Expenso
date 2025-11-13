<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Transactions</h1>
                        <p class="text-gray-600 mt-2">Track all your income and expenses</p>
                    </div>
                    <NuxtLink to="/transactions/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                    </NuxtLink>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="status === 'pending'" class="flex items-center justify-center py-20">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Loading transactions...</p>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <Icon name="heroicons:exclamation-triangle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-red-900 mb-2">Failed to Load Transactions</h3>
                <p class="text-red-700 mb-4">{{ error.statusMessage || "An error occurred while fetching transactions." }}</p>
                <button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors" @click="refresh()">Try Again</button>
            </div>

            <!-- Success State -->
            <template v-else-if="status === 'success' && transactionsData">
                <!-- Empty State -->
                <div v-if="!transactionsData.transactions || transactionsData.transactions.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <Icon name="heroicons:banknotes" class="w-20 h-20 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Transactions Yet</h3>
                    <p class="text-gray-600 mb-6">Get started by adding your first income or expense transaction.</p>
                    <NuxtLink to="/transactions/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span>Add Your First Transaction</span>
                    </NuxtLink>
                </div>

                <!-- Transactions Exist -->
                <template v-else>
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-2">
                                <Icon name="heroicons:arrow-trending-up" class="w-8 h-8 text-green-500" />
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Total Income</p>
                            <p class="text-2xl font-bold text-green-600">{{ formatCurrency(transactionsData.total_income) }}</p>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-2">
                                <Icon name="heroicons:arrow-trending-down" class="w-8 h-8 text-red-500" />
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Total Expenses</p>
                            <p class="text-2xl font-bold text-red-600">{{ formatCurrency(transactionsData.total_expenses) }}</p>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-2">
                                <Icon name="heroicons:banknotes" class="w-8 h-8 text-indigo-500" />
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Net Balance</p>
                            <p :class="['text-2xl font-bold', transactionsData.total_income - transactionsData.total_expenses >= 0 ? 'text-green-600' : 'text-red-600']">
                                {{ formatCurrency(transactionsData.total_income - transactionsData.total_expenses) }}
                            </p>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-2">
                                <Icon name="heroicons:calendar" class="w-8 h-8 text-purple-500" />
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Total Transactions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ transactionsData.transactions.length }}</p>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                            <button class="flex items-center space-x-2 text-indigo-600 hover:text-indigo-700 text-sm font-medium" @click="refresh()">
                                <Icon name="heroicons:arrow-path" class="w-4 h-4" />
                                <span>Refresh</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Search -->
                            <div class="md:col-span-2 relative">
                                <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                                <input type="text" placeholder="Search transactions..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            </div>

                            <!-- Type Filter -->
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="all">All Types</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                                <option value="transfer">Transfer</option>
                            </select>

                            <!-- Category Filter -->
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="all">All Categories</option>
                                <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                            </select>

                            <!-- Sort -->
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="date-desc">Latest</option>
                                <option value="date-asc">Oldest</option>
                                <option value="amount-desc">Highest</option>
                                <option value="amount-asc">Lowest</option>
                            </select>
                        </div>

                        <!-- Quick Filters -->
                        <div class="flex items-center space-x-2 mt-4">
                            <span class="text-sm text-gray-600 font-medium">Quick Filter:</span>
                            <button :class="[quickFilter === 'today' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="quickFilter = 'today'">
                                Today
                            </button>
                            <button :class="[quickFilter === 'week' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="quickFilter = 'week'">
                                This Week
                            </button>
                            <button :class="[quickFilter === 'month' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="quickFilter = 'month'">
                                This Month
                            </button>
                            <button :class="[quickFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="quickFilter = 'all'">
                                All Time
                            </button>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="transaction in transactionsData.transactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors">
                                        <!-- Date & Time -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ formatDate(transaction.transaction_date) }}</div>
                                            <div class="text-xs text-gray-500">{{ formatTime(transaction.transaction_date) }}</div>
                                        </td>

                                        <!-- Name -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm text-gray-900">{{ transaction.name }}</p>
                                            </div>
                                        </td>

                                        <!-- Category -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div v-if="transaction.category" class="flex items-center space-x-2">
                                                <div :style="{ backgroundColor: transaction.category.color + '20' }" class="w-8 h-8 rounded-lg flex items-center justify-center">
                                                    <Icon :name="transaction.category.icon" class="w-4 h-4" :style="{ color: transaction.category.color }" />
                                                </div>
                                                <span class="text-sm text-gray-900">{{ transaction.category.name }}</span>
                                            </div>

                                            <div v-else class="flex items-center space-x-2">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-100">
                                                    <Icon name="heroicons:arrow-path" class="w-4 h-4 text-blue-600" />
                                                </div>
                                                <span class="text-sm text-gray-900">Transfer</span>
                                            </div>
                                        </td>

                                        <!-- Account -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <Icon :name="transaction.account.icon" class="w-4 h-4 text-gray-400" />
                                                <span class="text-sm text-gray-900">{{ transaction.account.name }}</span>
                                            </div>
                                        </td>

                                        <!-- Type -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="transaction.category" :class="[transaction.category.type === 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600', 'px-3 py-1 text-xs font-medium rounded-full']">
                                                {{ transaction.category.type === "income" ? "Income" : "Expense" }}
                                            </span>

                                            <span v-else class="bg-blue-100 text-blue-600 px-3 py-1 text-xs font-medium rounded-full"> Transfer </span>
                                        </td>

                                        <!-- Amount -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span v-if="transaction.category" :class="[transaction.category.type === 'income' ? 'text-green-600' : 'text-red-600', 'text-sm font-semibold']">
                                                {{ transaction.category.type === "income" ? "+" : "-" }}{{ formatCurrency(transaction.category.type === "income" ? transaction.credit : transaction.debit) }}
                                            </span>

                                            <span v-else class="text-blue-600 text-sm font-semibold">
                                                {{ formatCurrency(transaction.debit || transaction.credit || 0) }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <NuxtLink :to="`/transactions/${transaction.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-100 transition-colors">
                                                    <Icon name="heroicons:eye" class="w-4 h-4" />
                                                </NuxtLink>
                                                <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" :disabled="isDeleting" @click="deleteTransaction(transaction.id)">
                                                    <Icon name="heroicons:trash" class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="text-sm text-gray-600">Showing {{ transactionsData.transactions.length }} of {{ transactionsData.transactions.length }} transactions</div>
                            <div class="flex items-center space-x-2">
                                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium">1</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">2</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">3</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Next</button>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Transactions - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { deleteTransaction: deleteAction } = useTransactions();
    const { formatCurrency } = useCurrency();
    const { formatDate, formatTime } = useUtils();
    const { success, error: toastError } = useToast();
    const client = useSanctumClient();

    const { data: transactionsData, status, error, refresh } = await useAsyncData("transactions", () => client("/api/transactions"));

    // TODO:: Filter, sorting and searching
    const quickFilter = ref("all");
    const isDeleting = ref(false);

    const categories = computed(() => {
        if (!transactionsData.value?.transactions) return [];
        return [...new Set(transactionsData.value.transactions.filter((t) => t.category !== null).map((t) => t.category.name))];
    });

    const deleteTransaction = async (id) => {
        if (confirm("Are you sure you want to delete this transaction?")) {
            try {
                await deleteAction(id);
                success("Transaction deleted!");
                await refresh();
            } catch (err) {
                console.log(err);
                toastError("Transaction fail to delete.");
            }
        }
    };
</script>
