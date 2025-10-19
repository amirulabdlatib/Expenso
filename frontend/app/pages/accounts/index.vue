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

            <!-- Loading State -->
            <div v-if="status === 'pending'" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600" />
                <p class="mt-4 text-gray-600">Loading accounts...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <Icon name="heroicons:exclamation-triangle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-red-900 mb-2">Error Loading Accounts</h3>
                <p class="text-red-700 mb-4">{{ error.message }}</p>
                <button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors" @click="refresh">Try Again</button>
            </div>

            <template v-else>
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <Icon name="heroicons:wallet" class="w-8 h-8 opacity-80" />
                            <span class="text-sm opacity-80">Total Balance</span>
                        </div>
                        <p class="text-3xl font-bold">{{ formatCurrency(accountsData?.totalBalance || 0) }}</p>
                        <p class="text-sm opacity-80 mt-2">Across {{ totalAccounts }} accounts</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <Icon name="heroicons:arrow-trending-up" class="w-8 h-8 opacity-80" />
                            <span class="text-sm opacity-80">Active Accounts</span>
                        </div>
                        <p class="text-3xl font-bold">{{ accountsData?.active_accounts || 0 }}</p>
                        <p class="text-sm opacity-80 mt-2">{{ accountsData?.inactiveAccounts || 0 }} inactive</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <Icon name="heroicons:credit-card" class="w-8 h-8 opacity-80" />
                            <span class="text-sm opacity-80">Active Balance</span>
                        </div>
                        <p class="text-3xl font-bold">{{ formatCurrency(accountsData?.activeAccountsBalance || 0) }}</p>
                        <p class="text-sm opacity-80 mt-2">From active accounts</p>
                    </div>
                </div>

                <!-- Filter and Sort -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <button :class="[filterType === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']" @click="filterType = 'all'">
                                All
                            </button>
                            <button :class="[filterType === 'bank' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']" @click="filterType = 'bank'">
                                Bank
                            </button>
                            <button
                                :class="[filterType === 'credit' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']"
                                @click="filterType = 'credit'"
                            >
                                Credit Card
                            </button>
                            <button :class="[filterType === 'cash' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']" @click="filterType = 'cash'">
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
                        <div class="p-6 text-white relative bg-gradient-to-br from-indigo-500 to-indigo-600">
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
                                </div>
                            </div>
                            <div>
                                <p class="text-sm opacity-80 mb-1">Current Balance</p>
                                <p class="text-3xl font-bold">{{ formatCurrency(account.balance) }}</p>
                            </div>
                            <div v-if="account.accountNumber" class="mt-4 text-sm opacity-80">•••• {{ account.accountNumber.slice(-4) }}</div>
                            <div v-if="account.accountNumber" class="mt-4 text-sm opacity-80">•••• {{ account.accountNumber.slice(-4) }}</div>
                        </div>

                        <!-- Account Details -->
                        <div class="p-6 space-y-4">
                            <!-- Quick Stats -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Currency</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ account.currency || "MYR" }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Type</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ account.type }}</p>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span :class="[account.is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600', 'px-3 py-1 text-xs font-medium rounded-full']">{{ account.is_active ? "Active" : "Inactive" }}</span>
                                <NuxtLink :to="`/accounts/edit/${account.id}`" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center space-x-1">
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
                    <p class="text-gray-600 mb-6">{{ searchQuery || filterType !== "all" ? "Try adjusting your filters" : "Add your first account to start tracking your finances" }}</p>
                    <NuxtLink to="/accounts/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span>Add Account</span>
                    </NuxtLink>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Accounts - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { formatCurrency } = useCurrency();

    const client = useSanctumClient();

    const { data: accountsData, status, error, refresh } = await useAsyncData("accounts", () => client("/api/accounts"));

    const totalAccounts = computed(() => {
        const data = accountsData.value || {};
        return (data.active_accounts || 0) + (data.inactiveAccounts || 0);
    });

    // State
    const searchQuery = ref("");
    const filterType = ref("all");

    // Computed - will be empty until backend returns account list
    const filteredAccounts = computed(() => {
        return [];
    });
</script>
