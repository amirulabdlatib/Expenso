<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Budgets</h1>
                        <p class="text-gray-600 mt-2">Set and track your monthly spending limits</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <NuxtLink to="/budgets/create" class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <span class="text-xl font-light">+</span>
                        </NuxtLink>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <p class="text-sm font-medium text-gray-500 mb-2">Total Budget</p>
                    <p class="text-lg font-bold text-gray-900 mb-1">{{ formatCurrency(totalBudget) }}</p>
                    <p class="text-xs text-gray-500">{{ budgets.length }} categories</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <p class="text-sm font-medium text-gray-500 mb-2">Total Spent</p>
                    <p class="text-lg font-bold text-red-600 mb-1">{{ formatCurrency(totalSpent) }}</p>
                    <p class="text-xs text-gray-500">{{ ((totalSpent / totalBudget) * 100).toFixed(1) }}% of budget</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <p class="text-sm font-medium text-gray-500 mb-2">Remaining</p>
                    <p class="text-lg font-bold text-green-600 mb-1">{{ formatCurrency(totalRemaining) }}</p>
                    <p class="text-xs text-gray-500">{{ ((totalRemaining / totalBudget) * 100).toFixed(1) }}% available</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <p class="text-sm font-medium text-gray-500 mb-2">Alerts</p>
                    <p class="text-lg font-bold text-yellow-600 mb-1">{{ alertsCount }}</p>
                    <p class="text-xs text-gray-500">Budgets need attention</p>
                </div>
            </div>

            <!-- Filter and Sort -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button :class="[filterStatus === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']" @click="filterStatus = 'all'">
                            All
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <select v-model="selectedMonth" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option v-for="(month, index) in months" :key="index" :value="index + 1">
                                {{ month }}
                            </option>
                        </select>
                        <select v-model="selectedYear" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option v-for="year in years" :key="year" :value="year">
                                {{ year }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Budgets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="budget in filteredBudgets" :key="budget.id" class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all overflow-hidden">
                    <!-- Budget Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">{{ budget.category }}</h3>
                            </div>
                            <div class="flex items-center space-x-1">
                                <NuxtLink :to="`/budgets/${budget.id}/edit`" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-50 transition-colors">
                                    <span class="text-sm">✎</span>
                                </NuxtLink>
                                <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" @click="deleteBudget(budget.id)">
                                    <span class="text-sm">✕</span>
                                </button>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center space-x-2">
                            <span :class="[getStatusClass(budget), 'px-3 py-1 text-xs font-medium rounded-full']">{{ getStatusText(budget) }}</span>
                            <span v-if="getSmartStatus(budget) === 'warning'" class="px-3 py-1 text-xs font-medium bg-yellow-50 text-yellow-600 rounded-full"> Alert </span>
                        </div>
                    </div>

                    <!-- Budget Details -->
                    <div class="p-6 space-y-4">
                        <!-- Amount Info -->
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Spent</p>
                                <p class="text-lg font-bold text-gray-900">{{ formatCurrency(budget.spent) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 mb-1">Limit</p>
                                <p class="text-lg font-bold text-gray-900">{{ formatCurrency(budget.limit) }}</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-2">
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div :class="[getProgressColor(budget), 'h-2 rounded-full transition-all']" :style="{ width: Math.min(budget.percentage, 100) + '%' }" />
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">{{ Math.min(budget.percentage, 100) }}% used</span>
                                <span :class="[budget.remaining >= 0 ? 'text-green-600' : 'text-red-600', 'font-semibold']">
                                    {{ budget.remaining >= 0 ? formatCurrency(budget.remaining) + " left" : formatCurrency(Math.abs(budget.remaining)) + " over" }}
                                </span>
                            </div>
                        </div>

                        <!-- Transactions -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">{{ budget.transactionCount }} transactions</span>
                                <NuxtLink :to="`/budgets/${budget.id}`" class="text-indigo-600 hover:text-indigo-700 font-medium"> View Details → </NuxtLink>
                            </div>
                        </div>

                        <!-- Daily Average & Projection (for monthly budgets) -->
                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Daily average</span>
                                <span class="font-semibold text-gray-900">{{ formatCurrency(getDailyAverage(budget)) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Projected month-end</span>
                                <span :class="[getProjectedEnd(budget) > budget.limit ? 'text-red-600' : 'text-gray-900', 'font-semibold']">
                                    {{ formatCurrency(getProjectedEnd(budget)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredBudgets.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl text-gray-400">📊</span>
                </div>

                <!-- When no budgets exist at all -->
                <template v-if="budgets.length === 0">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No budgets found</h3>
                    <p class="text-gray-600 mb-6">Create your first budget to start tracking your spending</p>
                    <NuxtLink to="/budgets/create" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <span class="text-lg font-light">+</span>
                        <span>Create Budget</span>
                    </NuxtLink>
                </template>

                <!-- When filter returns no results -->
                <template v-else>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No budgets match your criteria</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters</p>
                    <button class="inline-flex items-center justify-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors" @click="clearFilters">
                        <span>Clear Filters</span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Budgets - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    // State
    const selectedMonth = ref(new Date().getMonth() + 1);
    const selectedYear = ref(new Date().getFullYear());

    const filterStatus = ref("all");
    const { formatCurrency } = useCurrency();
    const { getDailyAverage, getProjectedEnd, getSmartStatus, getStatusClass, getStatusText, getProgressColor, months, years } = useBudgetUtils();

    // Dummy Data
    const budgets = ref([
        {
            id: 1,
            category: "Food & Groceries",
            limit: 1500.0,
            spent: 1245.5,
            percentage: 83,
            remaining: 254.5,
            transactionCount: 45,
        },
        {
            id: 2,
            category: "Transportation",
            limit: 800.0,
            spent: 890.0,
            percentage: 111,
            remaining: -90.0,
            transactionCount: 32,
        },
        {
            id: 3,
            category: "Entertainment",
            limit: 1000.0,
            spent: 700.0,
            percentage: 70,
            remaining: 300.0,
            transactionCount: 28,
        },
        {
            id: 4,
            category: "Shopping",
            limit: 600.0,
            spent: 580.0,
            percentage: 97,
            remaining: 20.0,
            transactionCount: 24,
        },
        {
            id: 5,
            category: "Healthcare",
            limit: 500.0,
            spent: 235.0,
            percentage: 47,
            remaining: 265.0,
            transactionCount: 8,
        },
    ]);

    // Computed
    const totalBudget = computed(() => {
        return budgets.value.reduce((sum, b) => sum + b.limit, 0);
    });

    const totalSpent = computed(() => {
        return budgets.value.reduce((sum, b) => sum + b.spent, 0);
    });

    const totalRemaining = computed(() => {
        return totalBudget.value - totalSpent.value;
    });

    const alertsCount = computed(() => {
        return budgets.value.filter((b) => getSmartStatus(b) === "warning" || getSmartStatus(b) === "over-budget").length;
    });

    const filteredBudgets = computed(() => {
        let filtered = budgets.value;

        // Status filter
        if (filterStatus.value !== "all") {
            filtered = filtered.filter((b) => getSmartStatus(b) === filterStatus.value);
        }

        return filtered;
    });

    const clearFilters = () => {
        filterStatus.value = "all";
    };

    const deleteBudget = (id) => {
        if (confirm("Are you sure you want to delete this budget?")) {
            const index = budgets.value.findIndex((b) => b.id === id);
            if (index !== -1) {
                budgets.value.splice(index, 1);
            }
        }
    };
</script>
