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
                        <select v-model="selectedPeriod" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="current">Current Month</option>
                            <option value="last">Last Month</option>
                            <option value="year">This Year</option>
                        </select>
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
                        <button
                            :class="[filterStatus === 'on-track' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']"
                            @click="filterStatus = 'on-track'">
                            On Track
                        </button>
                        <button
                            :class="[filterStatus === 'warning' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']"
                            @click="filterStatus = 'warning'">
                            Warning
                        </button>
                        <button
                            :class="[filterStatus === 'over-budget' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']"
                            @click="filterStatus = 'over-budget'">
                            Over Budget
                        </button>
                    </div>
                    <div class="relative">
                        <input v-model="searchQuery" type="text" placeholder="Search budgets..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64" />
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
                                <p class="text-sm text-gray-500 mt-1">{{ budget.period }}</p>
                            </div>
                            <div class="flex items-center space-x-1">
                                <NuxtLink :to="`/budgets/edit/${budget.id}`" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-50 transition-colors">
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
                        <div v-if="budget.period === 'Monthly'" class="pt-4 border-t border-gray-100 space-y-2">
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
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No budgets found</h3>
                <p class="text-gray-600 mb-6">Create your first budget to start tracking your spending</p>
                <NuxtLink to="/budgets/create" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                    <span class="text-lg font-light">+</span>
                    <span>Create Budget</span>
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Budgets - Expenso",
    });

    // State
    const selectedPeriod = ref("current");
    const searchQuery = ref("");
    const filterStatus = ref("all");

    // Dummy Data
    const budgets = ref([
        {
            id: 1,
            category: "Food & Groceries",
            color: "#10B981",
            limit: 1500.0,
            spent: 1245.5,
            percentage: 83,
            remaining: 254.5,
            period: "Monthly",
            transactionCount: 45,
        },
        {
            id: 2,
            category: "Transportation",
            color: "#3B82F6",
            limit: 800.0,
            spent: 890.0,
            percentage: 111,
            remaining: -90.0,
            period: "Monthly",
            transactionCount: 32,
        },
        {
            id: 3,
            category: "Entertainment",
            color: "#F59E0B",
            limit: 1000.0,
            spent: 650.0,
            percentage: 65,
            remaining: 350.0,
            period: "Monthly",
            transactionCount: 28,
        },
        {
            id: 4,
            category: "Shopping",
            color: "#EC4899",
            limit: 600.0,
            spent: 580.0,
            percentage: 97,
            remaining: 20.0,
            period: "Monthly",
            transactionCount: 24,
        },
        {
            id: 5,
            category: "Healthcare",
            color: "#EF4444",
            limit: 500.0,
            spent: 235.0,
            percentage: 47,
            remaining: 265.0,
            period: "Monthly",
            transactionCount: 8,
        },
        {
            id: 6,
            category: "Utilities",
            color: "#8B5CF6",
            limit: 450.0,
            spent: 420.0,
            percentage: 93,
            remaining: 30.0,
            period: "Monthly",
            transactionCount: 12,
        },
        {
            id: 7,
            category: "Education",
            color: "#06B6D4",
            limit: 300.0,
            spent: 150.0,
            percentage: 50,
            remaining: 150.0,
            period: "Monthly",
            transactionCount: 5,
        },
        {
            id: 8,
            category: "Dining Out",
            color: "#F97316",
            limit: 400.0,
            spent: 385.0,
            percentage: 96,
            remaining: 15.0,
            period: "Monthly",
            transactionCount: 18,
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

        // Search filter
        if (searchQuery.value) {
            filtered = filtered.filter((b) => b.category.toLowerCase().includes(searchQuery.value.toLowerCase()));
        }

        // Status filter
        if (filterStatus.value !== "all") {
            filtered = filtered.filter((b) => getSmartStatus(b) === filterStatus.value);
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

    const getDailyAverage = (budget) => {
        const today = new Date();
        const currentDay = today.getDate();
        return budget.spent / currentDay;
    };

    const getProjectedEnd = (budget) => {
        const today = new Date();
        const currentDay = today.getDate();
        const daysInMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
        const dailyAverage = budget.spent / currentDay;
        return dailyAverage * daysInMonth;
    };

    const getSmartStatus = (budget) => {
        if (budget.percentage >= 100) return "over-budget";

        const today = new Date();
        const currentDay = today.getDate();
        const daysInMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
        const expectedPercentage = (currentDay / daysInMonth) * 100;

        // If spending is more than 10% ahead of schedule
        if (budget.percentage > expectedPercentage + 10) return "warning";

        return "on-track";
    };

    const getStatusClass = (budget) => {
        const status = getSmartStatus(budget);
        if (status === "over-budget") return "bg-red-100 text-red-600";
        if (status === "warning") return "bg-yellow-100 text-yellow-600";
        return "bg-green-100 text-green-600";
    };

    const getStatusText = (budget) => {
        const status = getSmartStatus(budget);
        if (status === "over-budget") return "Over Budget";
        if (status === "warning") return "Warning";
        return "On Track";
    };

    const getProgressColor = (budget) => {
        const status = getSmartStatus(budget);
        if (status === "over-budget") return "bg-red-500";
        if (status === "warning") return "bg-yellow-500";
        return "bg-green-500";
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
