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
            <DashboardHeader v-model="isStatsVisible" />
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Balance -->
                <DashboardStatsTotalBalance :total-balance="Number(data?.total_balance ?? 0)" :is-visible="isStatsVisible" />
                <!-- Income -->
                <DashboardStatsIncome :monthly-income="Number(data?.monthly_income ?? 0)" :is-visible="isStatsVisible" />
                <!-- Expenses -->
                <DashboardStatsExpenses :monthly-expenses="Number(data?.monthly_expenses ?? 0)" :is-visible="isStatsVisible" />
            </div>

            <!-- Charts and Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Transactions -->
                <DashboardRecentTransactions :recent-transactions="recentTransactions" />
                <!-- Budget Overview -->
                <DashboardBudgetOverview />
            </div>
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

    const client = useSanctumClient();

    const { data, pending, error, refresh } = await useAsyncData("dashboard", () => client("/api/dashboard"));

    const recentTransactions = computed(() => data.value?.transactions || []);

    const isStatsVisible = ref(false);
</script>
