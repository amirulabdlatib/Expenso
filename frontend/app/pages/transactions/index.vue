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

            <!-- Loading State (Initial Load Only) -->
            <div v-if="status === 'pending' && !transactionsData" class="flex items-center justify-center py-20">
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
            <template v-else-if="transactionsData">
                <!-- 404 Not Found - Page out of bounds -->
                <div v-if="transactionsData.pagination && transactionsData.pagination.current_page > transactionsData.pagination.last_page" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <Icon name="heroicons:exclamation-triangle" class="w-20 h-20 text-yellow-500 mx-auto mb-4" />
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">404 - Page Not Found</h3>
                    <p class="text-gray-600 mb-6">The page you're looking for doesn't exist. There are only {{ transactionsData.pagination.last_page }} pages available.</p>
                    <button class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors" @click="changePage(1)">
                        <Icon name="heroicons:arrow-left" class="w-5 h-5" />
                        <span>Go to First Page</span>
                    </button>
                </div>

                <!-- Empty State - No Transactions at All -->
                <div v-else-if="(!transactionsData.transactions || transactionsData.transactions.length === 0) && !hasActiveFilters && !isClearing" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <Icon name="heroicons:banknotes" class="w-20 h-20 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Transactions Yet</h3>
                    <p class="text-gray-600 mb-6">Get started by adding your first income or expense transaction.</p>
                    <NuxtLink to="/transactions/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span>Add Your First Transaction</span>
                    </NuxtLink>
                </div>

                <!-- Transactions Exist or Search Results -->
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
                            <p class="text-2xl font-bold text-gray-900">{{ transactionsData.pagination.total }}</p>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Search -->
                            <div class="md:col-span-2 relative">
                                <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search transactions..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    @input="handleSearch" />
                            </div>

                            <!-- Type Filter -->
                            <select v-model="transactionFilterType" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" @change="handletransactionFilterType">
                                <option value="all">All Types</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                                <option value="transfer">Transfer</option>
                            </select>

                            <!-- Category Filter -->
                            <select v-model="categoryName" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" @change="handleCategoryNameFilter">
                                <option value="all">All Categories</option>
                                <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                            </select>

                            <!-- Sort -->
                            <select v-model="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" @change="handleSort">
                                <option value="date-desc">Latest</option>
                                <option value="date-asc">Oldest</option>
                                <option value="amount-desc">Highest</option>
                                <option value="amount-asc">Lowest</option>
                            </select>
                        </div>

                        <!-- Quick Filters -->
                        <div class="flex items-center space-x-2 mt-4">
                            <span class="text-sm text-gray-600 font-medium">Quick Filter:</span>
                            <button :class="[quickFilter === 'today' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="handleQuickFilter('today')">
                                Today
                            </button>
                            <button :class="[quickFilter === 'week' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="handleQuickFilter('week')">
                                This Week
                            </button>
                            <button :class="[quickFilter === 'month' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="handleQuickFilter('month')">
                                This Month
                            </button>
                            <button :class="[quickFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']" @click="handleQuickFilter('all')">
                                All Time
                            </button>
                        </div>
                    </div>

                    <!-- Transactions Table with Loading Overlay -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden relative">
                        <!-- Loading Overlay -->
                        <div v-if="isSearching || isClearing" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-2"></div>
                                <p class="text-sm text-gray-600">{{ isClearing ? "Loading..." : "Searching..." }}</p>
                            </div>
                        </div>

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
                                    <!-- No Search Results Row -->
                                    <tr v-if="!transactionsData.transactions || transactionsData.transactions.length === 0">
                                        <td colspan="7" class="px-6 py-16 text-center">
                                            <Icon name="heroicons:magnifying-glass-circle" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Results Found</h3>
                                            <p class="text-gray-600 mb-4">
                                                No transactions match your current filters
                                                <span v-if="searchQuery"> for "{{ searchQuery }}"</span>
                                            </p>
                                            <button class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm" @click="clearSearch">
                                                <Icon name="heroicons:x-mark" class="w-4 h-4" />
                                                <span>Clear All Filters</span>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Transaction Rows -->
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
                            <div class="text-sm text-gray-600">Showing {{ transactionsData.pagination.from }} to {{ transactionsData.pagination.to }} of {{ transactionsData.pagination.total }} entries</div>
                            <div class="flex items-center space-x-2">
                                <button
                                    class="px-3 py-1 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-50"
                                    :disabled="transactionsData.pagination.current_page === 1"
                                    @click="changePage(transactionsData.pagination.current_page - 1)">
                                    Previous
                                </button>

                                <!-- Page numbers -->
                                <template v-for="pageNumber in getPageNumbers" :key="pageNumber">
                                    <button v-if="pageNumber === '...'" class="px-3 py-1 text-gray-600" disabled>
                                        {{ pageNumber }}
                                    </button>
                                    <button
                                        v-else
                                        class="px-3 py-1 border rounded-lg"
                                        :class="{
                                            'border-indigo-500 bg-indigo-50 text-indigo-600': pageNumber === transactionsData.pagination.current_page,
                                            'border-gray-300 text-gray-600 hover:bg-gray-50': pageNumber !== transactionsData.pagination.current_page,
                                        }"
                                        @click="changePage(pageNumber)">
                                        {{ pageNumber }}
                                    </button>
                                </template>

                                <button
                                    class="px-3 py-1 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 disabled:opacity-50"
                                    :disabled="transactionsData.pagination.current_page === transactionsData.pagination.last_page"
                                    @click="changePage(transactionsData.pagination.current_page + 1)">
                                    Next
                                </button>
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

    const quickFilter = ref("all");
    const searchQuery = ref("");
    const transactionFilterType = ref("all");
    const categoryName = ref("all");
    const sortBy = ref("date-desc");
    const isDeleting = ref(false);
    const isSearching = ref(false);
    const isClearing = ref(false);

    const route = useRoute();
    const router = useRouter();

    if (route.query.q) {
        searchQuery.value = route.query.q;
    }

    if (route.query.type) {
        transactionFilterType.value = route.query.type;
    }

    if (route.query.categoryName) {
        categoryName.value = route.query.categoryName;
    }

    if (route.query.filter) {
        quickFilter.value = route.query.filter;
    }

    if (route.query.sort) {
        sortBy.value = route.query.sort;
    }

    const page = computed(() => {
        const page = parseInt(route.query.page) || 1;
        return page > 0 ? page : 1;
    });

    const {
        data: transactionsData,
        status,
        error,
        refresh,
    } = await useAsyncData("transactions", () =>
        client("/api/transactions", {
            params: {
                search: searchQuery.value || undefined,
                type: transactionFilterType.value || "all",
                categoryName: categoryName.value || "all",
                filter: quickFilter.value !== "all" ? quickFilter.value : undefined,
                sort: sortBy.value || "date-desc",
                page: page.value,
                per_page: 10, // Match the default in the backend
            },
        })
    );

    const categories = computed(() => {
        if (!transactionsData.value?.categories) return [];
        return transactionsData.value.categories.map((category) => category.name);
    });

    // Computed property to check if any filter is active
    const hasActiveFilters = computed(() => {
        return searchQuery.value || transactionFilterType.value !== "all" || categoryName.value !== "all" || quickFilter.value !== "all";
    });

    const handleSearch = async () => {
        isSearching.value = true;
        const query = { ...route.query };
        if (searchQuery.value) {
            query.q = searchQuery.value;
        } else {
            delete query.q;
        }

        // Reset to first page when searching
        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handletransactionFilterType = async () => {
        isSearching.value = true;
        const query = { ...route.query };

        if (transactionFilterType.value && transactionFilterType.value !== "all") {
            query.type = transactionFilterType.value;
        } else {
            delete query.type;
        }

        // Reset to first page when changing transaction type filter
        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handleCategoryNameFilter = async () => {
        isSearching.value = true;
        const query = { ...route.query };

        if (categoryName.value && categoryName.value !== "all") {
            query.categoryName = categoryName.value;
        } else {
            delete query.categoryName;
        }

        // Reset to first page when changing category filter
        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handleQuickFilter = async (filter) => {
        isSearching.value = true;
        quickFilter.value = filter;
        const query = { ...route.query };

        if (filter && filter !== "all") {
            query.filter = filter;
        } else {
            delete query.filter;
        }

        // Reset to first page when applying quick filter
        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handleSort = async () => {
        isSearching.value = true;
        const query = { ...route.query };

        if (sortBy.value && sortBy.value !== "date-desc") {
            query.sort = sortBy.value;
        } else {
            delete query.sort;
        }

        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const clearSearch = async () => {
        isClearing.value = true;
        searchQuery.value = "";
        transactionFilterType.value = "all";
        categoryName.value = "all";
        quickFilter.value = "all";
        sortBy.value = "date-desc";
        await router.push({ query: {} });
        await refresh();
        isClearing.value = false;
    };

    // Change page handler
    const changePage = async (page) => {
        if (page < 1 || page > transactionsData.value.pagination.last_page) return;

        const query = { ...route.query, page };
        await router.push({ query });
        await refresh();
    };

    const getPageNumbers = computed(() => {
        if (!transactionsData.value?.pagination) return [];

        const current = transactionsData.value.pagination.current_page;
        const last = transactionsData.value.pagination.last_page;
        const delta = 1;
        const range = [];

        for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
            range.push(i);
        }

        if (current - delta > 2) {
            range.unshift("...");
        }

        if (current + delta < last - 1) {
            range.push("...");
        }

        if (last > 1) {
            range.unshift(1);
            range.push(last);
        } else {
            range.unshift(1);
        }

        return range;
    });

    const deleteTransaction = async (id) => {
        if (confirm("Are you sure you want to delete this transaction?")) {
            try {
                isDeleting.value = true;
                await deleteAction(id);
                success("Transaction deleted!");
                await refresh();
            } catch (err) {
                console.log(err);
                toastError("Transaction fail to delete.");
            } finally {
                isDeleting.value = false;
            }
        }
    };
</script>
