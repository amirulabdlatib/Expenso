<template>
    <div>
        <!-- Loading State -->
        <div v-if="pending" class="flex items-center justify-center py-20">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading dashboard data...</p>
            </div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
            <Icon name="heroicons:exclamation-triangle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-red-900 mb-2">Failed to Load Dashboard</h3>
            <p class="text-red-700 mb-4">{{ error.statusMessage || "An error occurred while fetching dashboard data." }}</p>
            <button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center mx-auto" :disabled="pending" @click="refresh">
                <Icon name="heroicons:arrow-path" class="w-5 h-5 mr-2" :class="{ 'animate-spin': pending }" />
                Try Again
            </button>
        </div>

        <!-- Content -->
        <template v-else>
            <DashboardHeader />
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Balance -->
                <DashboardStatsTotalBalance :total-balance="data?.total_balance ?? 0" />
                <!-- Income -->
                <DashboardStatsIncome :monthly-income="data?.monthly_income ?? 0" />
                <!-- Expenses -->
                <DashboardStatsExpenses :monthly-expenses="data?.monthly_expenses ?? 0" />
                <!-- Savings -->
                <DashboardStatsSavings />
            </div>

            <!-- Charts and Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Transactions -->
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

                <!-- Budget Overview -->
                <DashboardBudgetOverview />
            </div>

            <DashboardSavingGoals />
        </template>
    </div>
</template>

<script setup>
    definePageMeta({
        layout: "default",
        middleware: ["sanctum:auth"],
    });

    useHead({
        title: "Dashboard - Expenso",
    });

    const { formatRelativeDate } = useUtils();
    const { getColorClasses } = useCategoryConstant();
    const client = useSanctumClient();

    const { data, pending, error, refresh } = await useAsyncData("dashboard", () => client("/api/dashboard"));

    const recentTransactions = computed(() => data.value?.transactions || []);
</script>
