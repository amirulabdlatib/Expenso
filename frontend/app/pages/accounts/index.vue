<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Accounts</h1>
                        <p class="text-gray-600 mt-2">Manage your financial accounts and balances</p>
                    </div>
                    <NuxtLink to="/accounts/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                    </NuxtLink>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:wallet" class="w-8 h-8 opacity-80" />
                        <span class="text-sm opacity-80">Total Balance</span>
                    </div>
                    <p class="text-3xl font-bold">{{ formatCurrency(totalBalance) }}</p>
                    <p class="text-sm opacity-80 mt-2">Across {{ accounts.length }} accounts</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:arrow-trending-up" class="w-8 h-8 opacity-80" />
                        <span class="text-sm opacity-80">Active Accounts</span>
                    </div>
                    <p class="text-3xl font-bold">{{ activeAccounts }}</p>
                    <p class="text-sm opacity-80 mt-2">{{ inactiveAccounts }} inactive</p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:credit-card" class="w-8 h-8 opacity-80" />
                        <span class="text-sm opacity-80">Credit Available</span>
                    </div>
                    <p class="text-3xl font-bold">{{ formatCurrency(creditAvailable) }}</p>
                    <p class="text-sm opacity-80 mt-2">{{ formatCurrency(creditUsed) }} used</p>
                </div>
            </div>

            <!-- Filter and Sort -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <button @click="filterType = 'all'" :class="[filterType === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            All
                        </button>
                        <button @click="filterType = 'bank'" :class="[filterType === 'bank' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            Bank
                        </button>
                        <button @click="filterType = 'credit'" :class="[filterType === 'credit' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            Credit Card
                        </button>
                        <button @click="filterType = 'cash'" :class="[filterType === 'cash' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            Cash
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                            <input v-model="searchQuery" type="text" placeholder="Search accounts..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="account in filteredAccounts" :key="account.id" class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all overflow-hidden">
                    <!-- Account Header -->
                    <div :style="{ background: `linear-gradient(135deg, ${account.color} 0%, ${account.color}dd 100%)` }" class="p-6 text-white relative">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                    <Icon :name="account.icon" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">{{ account.name }}</h3>
                                    <p class="text-sm opacity-80">{{ account.type }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <NuxtLink :to="`/accounts/edit/${account.id}`" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                                    <Icon name="heroicons:pencil" class="w-4 h-4" />
                                </NuxtLink>
                                <button @click="deleteAccount(account.id)" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                                    <Icon name="heroicons:trash" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm opacity-80 mb-1">Current Balance</p>
                            <p class="text-3xl font-bold">{{ formatCurrency(account.balance) }}</p>
                        </div>
                        <div v-if="account.accountNumber" class="mt-4 text-sm opacity-80">•••• {{ account.accountNumber.slice(-4) }}</div>
                    </div>

                    <!-- Account Details -->
                    <div class="p-6 space-y-4">
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">This Month</p>
                                <p class="text-lg font-semibold text-gray-900">{{ formatCurrency(account.monthlySpent) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Transactions</p>
                                <p class="text-lg font-semibold text-gray-900">{{ account.transactionCount }}</p>
                            </div>
                        </div>

                        <!-- Credit Card Info -->
                        <div v-if="account.type === 'Credit Card'" class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-600">Credit Limit</span>
                                <span class="font-semibold text-gray-900">{{ formatCurrency(account.creditLimit) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div :style="{ width: account.creditUsage + '%' }" class="bg-gradient-to-r from-green-500 to-red-500 h-2 rounded-full"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ account.creditUsage }}% used</p>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span :class="[account.isActive ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600', 'px-3 py-1 text-xs font-medium rounded-full']">{{ account.isActive ? "Active" : "Inactive" }}</span>
                            <NuxtLink :to="`/accounts/${account.id}`" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center space-x-1">
                                <span>View Details</span>
                                <Icon name="heroicons:arrow-right" class="w-4 h-4" />
                            </NuxtLink>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredAccounts.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <Icon name="heroicons:building-library" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No accounts found</h3>
                <p class="text-gray-600 mb-6">Add your first account to start tracking your finances</p>
                <NuxtLink to="/accounts/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                    <Icon name="heroicons:plus" class="w-5 h-5" />
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Accounts - Expenso",
    });

    // Dummy Data
    const accounts = ref([
        {
            id: 1,
            name: "Maybank Savings",
            type: "Bank Account",
            balance: 15420.5,
            icon: "heroicons:building-library",
            color: "#3B82F6",
            accountNumber: "1234567890",
            monthlySpent: 3250.0,
            transactionCount: 45,
            isActive: true,
        },
        {
            id: 2,
            name: "CIMB Credit Card",
            type: "Credit Card",
            balance: -2340.0,
            creditLimit: 10000,
            creditUsage: 23.4,
            icon: "heroicons:credit-card",
            color: "#EF4444",
            accountNumber: "9876543210",
            monthlySpent: 2340.0,
            transactionCount: 32,
            isActive: true,
        },
        {
            id: 3,
            name: "Cash Wallet",
            type: "Cash",
            balance: 850.0,
            icon: "heroicons:banknotes",
            color: "#10B981",
            accountNumber: "",
            monthlySpent: 450.0,
            transactionCount: 28,
            isActive: true,
        },
        {
            id: 4,
            name: "Touch 'n Go eWallet",
            type: "E-Wallet",
            balance: 320.5,
            icon: "heroicons:device-phone-mobile",
            color: "#8B5CF6",
            accountNumber: "0123456789",
            monthlySpent: 180.5,
            transactionCount: 15,
            isActive: true,
        },
        {
            id: 5,
            name: "Public Bank Savings",
            type: "Bank Account",
            balance: 8750.0,
            icon: "heroicons:building-library",
            color: "#F59E0B",
            accountNumber: "5555666677",
            monthlySpent: 1200.0,
            transactionCount: 18,
            isActive: true,
        },
        {
            id: 6,
            name: "Emergency Fund",
            type: "Savings",
            balance: 25000.0,
            icon: "heroicons:shield-check",
            color: "#06B6D4",
            accountNumber: "",
            monthlySpent: 0,
            transactionCount: 2,
            isActive: true,
        },
    ]);

    // State
    const searchQuery = ref("");
    const filterType = ref("all");

    // Computed
    const totalBalance = computed(() => {
        return accounts.value.reduce((sum, acc) => sum + acc.balance, 0);
    });

    const activeAccounts = computed(() => {
        return accounts.value.filter((acc) => acc.isActive).length;
    });

    const inactiveAccounts = computed(() => {
        return accounts.value.filter((acc) => !acc.isActive).length;
    });

    const creditAvailable = computed(() => {
        return accounts.value.filter((acc) => acc.type === "Credit Card").reduce((sum, acc) => sum + (acc.creditLimit || 0) - Math.abs(acc.balance), 0);
    });

    const creditUsed = computed(() => {
        return accounts.value.filter((acc) => acc.type === "Credit Card").reduce((sum, acc) => sum + Math.abs(acc.balance), 0);
    });

    const filteredAccounts = computed(() => {
        let filtered = accounts.value;

        // Filter by type
        if (filterType.value !== "all") {
            filtered = filtered.filter((acc) => acc.type.toLowerCase().includes(filterType.value.toLowerCase()));
        }

        // Filter by search
        if (searchQuery.value) {
            filtered = filtered.filter((acc) => acc.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || acc.type.toLowerCase().includes(searchQuery.value.toLowerCase()));
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

    const deleteAccount = (id) => {
        if (confirm("Are you sure you want to delete this account?")) {
            const index = accounts.value.findIndex((acc) => acc.id === id);
            if (index !== -1) {
                accounts.value.splice(index, 1);
            }
        }
    };
</script>
