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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <Icon name="heroicons:arrow-trending-up" class="w-8 h-8 text-green-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Total Income</p>
                    <p class="text-2xl font-bold text-green-600">{{ formatCurrency(totalIncome) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <Icon name="heroicons:arrow-trending-down" class="w-8 h-8 text-red-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Total Expenses</p>
                    <p class="text-2xl font-bold text-red-600">{{ formatCurrency(totalExpenses) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <Icon name="heroicons:banknotes" class="w-8 h-8 text-indigo-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Net Balance</p>
                    <p class="text-2xl font-bold" :class="netBalance >= 0 ? 'text-green-600' : 'text-red-600'">{{ formatCurrency(netBalance) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <Icon name="heroicons:calendar" class="w-8 h-8 text-purple-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">This Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ transactions.length }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2 relative">
                        <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                        <input v-model="searchQuery" type="text" placeholder="Search transactions..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>

                    <!-- Type Filter -->
                    <select v-model="filterType" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">All Types</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>

                    <!-- Category Filter -->
                    <select v-model="filterCategory" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">All Categories</option>
                        <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                    </select>

                    <!-- Sort -->
                    <select v-model="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="date-desc">Latest First</option>
                        <option value="date-asc">Oldest First</option>
                        <option value="amount-desc">Highest Amount</option>
                        <option value="amount-asc">Lowest Amount</option>
                    </select>
                </div>

                <!-- Quick Filters -->
                <div class="flex items-center space-x-2 mt-4">
                    <span class="text-sm text-gray-600 font-medium">Quick Filter:</span>
                    <button @click="quickFilter = 'today'" :class="[quickFilter === 'today' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']">Today</button>
                    <button @click="quickFilter = 'week'" :class="[quickFilter === 'week' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']">This Week</button>
                    <button @click="quickFilter = 'month'" :class="[quickFilter === 'month' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']">This Month</button>
                    <button @click="quickFilter = 'all'" :class="[quickFilter === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-3 py-1 rounded-lg text-sm transition-colors']">All Time</button>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="transaction in filteredTransactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors">
                                <!-- Date & Time -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ formatDate(transaction.date) }}</div>
                                    <div class="text-xs text-gray-500">{{ formatTime(transaction.date) }}</div>
                                </td>

                                <!-- Description -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ transaction.description }}</div>
                                    <div v-if="transaction.note" class="text-xs text-gray-500 mt-1">{{ transaction.note }}</div>
                                </td>

                                <!-- Category -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <div :style="{ backgroundColor: transaction.categoryColor + '20' }" class="w-8 h-8 rounded-lg flex items-center justify-center">
                                            <Icon :name="transaction.categoryIcon" class="w-4 h-4" :style="{ color: transaction.categoryColor }" />
                                        </div>
                                        <span class="text-sm text-gray-900">{{ transaction.category }}</span>
                                    </div>
                                </td>

                                <!-- Account -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <Icon :name="transaction.accountIcon" class="w-4 h-4 text-gray-400" />
                                        <span class="text-sm text-gray-900">{{ transaction.account }}</span>
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[transaction.type === 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600', 'px-3 py-1 text-xs font-medium rounded-full']">{{
                                        transaction.type === "income" ? "Income" : "Expense"
                                    }}</span>
                                </td>

                                <!-- Amount -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span :class="[transaction.type === 'income' ? 'text-green-600' : 'text-red-600', 'text-sm font-semibold']">
                                        {{ transaction.type === "income" ? "+" : "-" }}{{ formatCurrency(Math.abs(transaction.amount)) }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <NuxtLink :to="`/transactions/${transaction.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-100 transition-colors">
                                            <Icon name="heroicons:eye" class="w-4 h-4" />
                                        </NuxtLink>
                                        <NuxtLink :to="`/transactions/edit/${transaction.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-100 transition-colors">
                                            <Icon name="heroicons:pencil" class="w-4 h-4" />
                                        </NuxtLink>
                                        <button @click="deleteTransaction(transaction.id)" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                            <Icon name="heroicons:trash" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="filteredTransactions.length === 0" class="text-center py-12">
                    <Icon name="heroicons:document-text" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No transactions found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or add a new transaction</p>
                    <NuxtLink to="/transactions/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                    </NuxtLink>
                </div>

                <!-- Pagination -->
                <div v-if="filteredTransactions.length > 0" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-600">Showing {{ filteredTransactions.length }} of {{ transactions.length }} transactions</div>
                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium">1</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">2</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">3</button>
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";

    useHead({
        title: "Transactions - Expenso",
    });

    // Dummy Data
    const transactions = ref([
        {
            id: 1,
            description: "Salary Payment",
            amount: 5000.0,
            type: "income",
            category: "Salary",
            categoryIcon: "heroicons:banknotes",
            categoryColor: "#10B981",
            account: "Maybank Savings",
            accountIcon: "heroicons:building-library",
            date: "2025-01-15T09:00:00",
            note: "Monthly salary",
        },
        {
            id: 2,
            description: "Grocery Shopping",
            amount: -156.5,
            type: "expense",
            category: "Food & Groceries",
            categoryIcon: "heroicons:shopping-bag",
            categoryColor: "#10B981",
            account: "CIMB Credit Card",
            accountIcon: "heroicons:credit-card",
            date: "2025-01-14T16:30:00",
            note: "Weekly groceries at Tesco",
        },
        {
            id: 3,
            description: "Fuel Top-up",
            amount: -80.0,
            type: "expense",
            category: "Transportation",
            categoryIcon: "heroicons:truck",
            categoryColor: "#3B82F6",
            account: "Cash Wallet",
            accountIcon: "heroicons:banknotes",
            date: "2025-01-14T12:15:00",
            note: "Petrol station",
        },
        {
            id: 4,
            description: "Freelance Project",
            amount: 1500.0,
            type: "income",
            category: "Freelance",
            categoryIcon: "heroicons:briefcase",
            categoryColor: "#3B82F6",
            account: "Maybank Savings",
            accountIcon: "heroicons:building-library",
            date: "2025-01-13T14:00:00",
            note: "Web development project",
        },
        {
            id: 5,
            description: "Netflix Subscription",
            amount: -55.0,
            type: "expense",
            category: "Entertainment",
            categoryIcon: "heroicons:film",
            categoryColor: "#F59E0B",
            account: "CIMB Credit Card",
            accountIcon: "heroicons:credit-card",
            date: "2025-01-12T10:00:00",
            note: "Monthly subscription",
        },
        {
            id: 6,
            description: "Lunch with Friends",
            amount: -45.0,
            type: "expense",
            category: "Food & Groceries",
            categoryIcon: "heroicons:shopping-bag",
            categoryColor: "#10B981",
            account: "Touch 'n Go eWallet",
            accountIcon: "heroicons:device-phone-mobile",
            date: "2025-01-11T13:30:00",
            note: "Italian restaurant",
        },
        {
            id: 7,
            description: "Electricity Bill",
            amount: -120.0,
            type: "expense",
            category: "Utilities",
            categoryIcon: "heroicons:bolt",
            categoryColor: "#8B5CF6",
            account: "Maybank Savings",
            accountIcon: "heroicons:building-library",
            date: "2025-01-10T09:00:00",
            note: "Monthly bill",
        },
        {
            id: 8,
            description: "Investment Returns",
            amount: 250.0,
            type: "income",
            category: "Investments",
            categoryIcon: "heroicons:chart-bar",
            categoryColor: "#F59E0B",
            account: "Public Bank Savings",
            accountIcon: "heroicons:building-library",
            date: "2025-01-09T11:00:00",
            note: "Dividend payment",
        },
        {
            id: 9,
            description: "New Shirt",
            amount: -89.9,
            type: "expense",
            category: "Shopping",
            categoryIcon: "heroicons:shopping-cart",
            categoryColor: "#EC4899",
            account: "CIMB Credit Card",
            accountIcon: "heroicons:credit-card",
            date: "2025-01-08T15:45:00",
            note: "Uniqlo",
        },
        {
            id: 10,
            description: "Doctor Visit",
            amount: -150.0,
            type: "expense",
            category: "Healthcare",
            categoryIcon: "heroicons:heart",
            categoryColor: "#EF4444",
            account: "Cash Wallet",
            accountIcon: "heroicons:banknotes",
            date: "2025-01-07T10:30:00",
            note: "General checkup",
        },
    ]);

    // State
    const searchQuery = ref("");
    const filterType = ref("all");
    const filterCategory = ref("all");
    const sortBy = ref("date-desc");
    const quickFilter = ref("all");

    // Computed
    const categories = computed(() => {
        return [...new Set(transactions.value.map((t) => t.category))];
    });

    const totalIncome = computed(() => {
        return transactions.value.filter((t) => t.type === "income").reduce((sum, t) => sum + t.amount, 0);
    });

    const totalExpenses = computed(() => {
        return Math.abs(transactions.value.filter((t) => t.type === "expense").reduce((sum, t) => sum + t.amount, 0));
    });

    const netBalance = computed(() => {
        return totalIncome.value - totalExpenses.value;
    });

    const filteredTransactions = computed(() => {
        let filtered = transactions.value;

        // Search filter
        if (searchQuery.value) {
            filtered = filtered.filter(
                (t) => t.description.toLowerCase().includes(searchQuery.value.toLowerCase()) || t.category.toLowerCase().includes(searchQuery.value.toLowerCase()) || t.account.toLowerCase().includes(searchQuery.value.toLowerCase())
            );
        }

        // Type filter
        if (filterType.value !== "all") {
            filtered = filtered.filter((t) => t.type === filterType.value);
        }

        // Category filter
        if (filterCategory.value !== "all") {
            filtered = filtered.filter((t) => t.category === filterCategory.value);
        }

        // Quick date filter
        const now = new Date();
        if (quickFilter.value === "today") {
            filtered = filtered.filter((t) => {
                const transDate = new Date(t.date);
                return transDate.toDateString() === now.toDateString();
            });
        } else if (quickFilter.value === "week") {
            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            filtered = filtered.filter((t) => new Date(t.date) >= weekAgo);
        } else if (quickFilter.value === "month") {
            const monthAgo = new Date(now.getFullYear(), now.getMonth(), 1);
            filtered = filtered.filter((t) => new Date(t.date) >= monthAgo);
        }

        // Sort
        if (sortBy.value === "date-desc") {
            filtered = [...filtered].sort((a, b) => new Date(b.date) - new Date(a.date));
        } else if (sortBy.value === "date-asc") {
            filtered = [...filtered].sort((a, b) => new Date(a.date) - new Date(b.date));
        } else if (sortBy.value === "amount-desc") {
            filtered = [...filtered].sort((a, b) => Math.abs(b.amount) - Math.abs(a.amount));
        } else if (sortBy.value === "amount-asc") {
            filtered = [...filtered].sort((a, b) => Math.abs(a.amount) - Math.abs(b.amount));
        }

        return filtered;
    });

    // Methods
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat("en-MY", {
            style: "currency",
            currency: "MYR",
        }).format(amount);
    };

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString("en-MY", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    };

    const formatTime = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleTimeString("en-MY", {
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const deleteTransaction = (id) => {
        if (confirm("Are you sure you want to delete this transaction?")) {
            const index = transactions.value.findIndex((t) => t.id === id);
            if (index !== -1) {
                transactions.value.splice(index, 1);
            }
        }
    };
</script>
