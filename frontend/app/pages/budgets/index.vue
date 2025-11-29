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
                        <button
                            :disabled="status === 'pending'"
                            class="flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            @click="refresh()"
                        >
                            <span :class="[status === 'pending' ? 'animate-spin' : '', 'text-xl']">↻</span>
                        </button>
                        <NuxtLink to="/budgets/create" class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <span class="text-xl font-light">+</span>
                        </NuxtLink>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="status === 'pending'" class="flex items-center justify-center py-20">
                <div class="text-center">
                    <div class="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-gray-600">Loading budgets...</p>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-white rounded-lg shadow-sm border border-red-200 p-12 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl text-red-600">⚠</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to load budgets</h3>
                <p class="text-gray-600 mb-6">{{ error.statusMessage || "An error occurred while fetching budgets" }}</p>
                <button class="inline-flex items-center justify-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors" @click="refresh()">
                    <span>Try Again</span>
                </button>
            </div>

            <!-- Success State -->
            <template v-else>
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
                        <p class="text-xs text-gray-500">{{ totalBudget > 0 ? ((totalSpent / totalBudget) * 100).toFixed(1) : "0.0" }}% of budget</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500 mb-2">Remaining</p>
                        <p class="text-lg font-bold text-green-600 mb-1">{{ formatCurrency(totalRemaining) }}</p>
                        <p class="text-xs text-gray-500">{{ totalBudget > 0 ? ((totalRemaining / totalBudget) * 100).toFixed(1) : "0.0" }}% available</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <p class="text-sm font-medium text-gray-500 mb-2">Alerts</p>
                        <p class="text-lg font-bold text-yellow-600 mb-1">{{ alertsCount }}</p>
                        <p class="text-xs text-gray-500">Budgets need attention</p>
                    </div>
                </div>

                <!-- Filter and Sort -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                    <div class="flex items-center justify-end space-x-2 mr-3">
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

                <!-- Budgets Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="budget in budgets" :key="budget.id" class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all overflow-hidden">
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
                                    <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" :disabled="isDeleting" @click="deleteBudget(budget.id)">
                                        <span class="text-sm">✕</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="flex items-center space-x-2">
                                <span :class="[getStatusClass(budget, selectedMonth, selectedYear), 'px-3 py-1 text-xs font-medium rounded-full']">{{ getStatusText(budget, selectedMonth, selectedYear) }}</span>
                                <span v-if="getSmartStatus(budget, selectedMonth, selectedYear) === 'warning'" class="px-3 py-1 text-xs font-medium bg-yellow-50 text-yellow-600 rounded-full"> Alert </span>
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
                                    <div :class="[getProgressColor(budget, selectedMonth, selectedYear), 'h-2 rounded-full transition-all']" :style="{ width: Math.min(budget.percentage, 100) + '%' }" />
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
                                    <span class="font-semibold text-gray-900">
                                        {{ formatCurrency(getDailyAverage(budget, selectedMonth, selectedYear)) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Projected month-end</span>
                                    <span :class="[getProjectedEnd(budget, selectedMonth, selectedYear) > budget.limit ? 'text-red-600' : 'text-gray-900', 'font-semibold']">
                                        {{ formatCurrency(getProjectedEnd(budget, selectedMonth, selectedYear)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="budgets.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl text-gray-400">📊</span>
                    </div>

                    <!-- When viewing default period with no budgets (truly empty) -->
                    <template v-if="isDefaultView">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No budgets found</h3>
                        <p class="text-gray-600 mb-6">Create your first budget to start tracking your spending</p>
                        <NuxtLink to="/budgets/create" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            <span class="text-lg font-light">+</span>
                            <span>Create Budget</span>
                        </NuxtLink>
                    </template>

                    <!-- When filter returns no results (filtered view) -->
                    <template v-else>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No budgets match your criteria</h3>
                        <p class="text-gray-600 mb-6">Try adjusting your filters or create a budget for this period</p>
                        <div class="flex items-center justify-center space-x-3">
                            <button class="inline-flex items-center justify-center space-x-2 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors" @click="clearFilters">
                                <span>Clear Filters</span>
                            </button>
                            <NuxtLink to="/budgets/create" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                                <span class="text-lg font-light">+</span>
                                <span>Create Budget</span>
                            </NuxtLink>
                        </div>
                    </template>
                </div>
            </template>
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
    const isDeleting = ref(false);

    const { formatCurrency } = useCurrency();
    const { deleteBudget: deleteAction } = useBudget();
    const { success, error: errorToast } = useToast();
    const client = useSanctumClient();
    const { getDailyAverage, getProjectedEnd, getSmartStatus, getStatusClass, getStatusText, getProgressColor, months, years } = useBudgetUtils();

    const { data, status, error, refresh } = await useAsyncData(
        "budgets",
        () =>
            client("/api/budgets", {
                params: {
                    month: selectedMonth.value,
                    year: selectedYear.value,
                },
            }),
        {
            watch: [selectedMonth, selectedYear],
        }
    );

    const budgets = computed(() => {
        return data.value?.budgets || [];
    });

    const isDefaultView = computed(() => {
        const now = new Date();
        return selectedMonth.value === now.getMonth() + 1 && selectedYear.value === now.getFullYear();
    });

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
        return budgets.value.filter((b) => {
            const status = getSmartStatus(b, selectedMonth.value, selectedYear.value);
            return status === "warning" || status === "over-budget";
        }).length;
    });

    const clearFilters = () => {
        selectedMonth.value = new Date().getMonth() + 1;
        selectedYear.value = new Date().getFullYear();
    };

    const deleteBudget = async (id) => {
        if (!confirm("Are you sure you want to delete this budget?")) {
            return;
        }
        isDeleting.value = true;
        try {
            await deleteAction(id);
            success("Budget deleted.");
            await refresh();
        } catch (err) {
            errorToast("Budget fail to delete: " + (err.statusMessage || "An unexpected error occurred"));
        } finally {
            isDeleting.value = false;
        }
    };
</script>
