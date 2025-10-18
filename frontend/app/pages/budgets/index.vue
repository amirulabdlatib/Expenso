<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Budgets</h1>
                        <p class="text-gray-600 mt-2">Set and track your spending limits</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select v-model="selectedPeriod" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="current">Current Month</option>
                            <option value="last">Last Month</option>
                            <option value="year">This Year</option>
                        </select>
                        <NuxtLink to="/budgets/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                            <Icon name="heroicons:plus" class="w-5 h-5" />
                        </NuxtLink>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:banknotes" class="w-8 h-8 text-indigo-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Total Budget</p>
                    <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(totalBudget) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Across {{ budgets.length }} categories</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:arrow-trending-down" class="w-8 h-8 text-red-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Total Spent</p>
                    <p class="text-2xl font-bold text-red-600">{{ formatCurrency(totalSpent) }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ ((totalSpent / totalBudget) * 100).toFixed(1) }}% of budget</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:wallet" class="w-8 h-8 text-green-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Remaining</p>
                    <p class="text-2xl font-bold text-green-600">{{ formatCurrency(totalRemaining) }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ ((totalRemaining / totalBudget) * 100).toFixed(1) }}% available</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:exclamation-triangle" class="w-8 h-8 text-yellow-500" />
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Alerts</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ alertsCount }}</p>
                    <p class="text-xs text-gray-500 mt-2">Budgets over 80%</p>
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
                            :class="[filterStatus === 'exceeded' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']"
                            @click="filterStatus = 'exceeded'">
                            Exceeded
                        </button>
                    </div>
                    <div class="relative">
                        <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                        <input v-model="searchQuery" type="text" placeholder="Search budgets..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64" >
                    </div>
                </div>
            </div>

            <!-- Budgets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="budget in filteredBudgets" :key="budget.id" class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all overflow-hidden">
                    <!-- Budget Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div :style="{ backgroundColor: budget.color + '20' }" class="w-12 h-12 rounded-lg flex items-center justify-center">
                                    <Icon :name="budget.icon" class="w-6 h-6" :style="{ color: budget.color }" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ budget.category }}</h3>
                                    <p class="text-sm text-gray-500">{{ budget.period }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <NuxtLink :to="`/budgets/edit/${budget.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-50 transition-colors">
                                    <Icon name="heroicons:pencil" class="w-4 h-4" />
                                </NuxtLink>
                                <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" @click="deleteBudget(budget.id)">
                                    <Icon name="heroicons:trash" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center space-x-2">
                            <span :class="[getStatusClass(budget.percentage), 'px-3 py-1 text-xs font-medium rounded-full']">{{ getStatusText(budget.percentage) }}</span>
                            <span v-if="budget.percentage > 80" class="flex items-center space-x-1 text-xs text-yellow-600">
                                <Icon name="heroicons:exclamation-triangle" class="w-4 h-4" />
                                <span>Alert</span>
                            </span>
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
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div :class="[getProgressColor(budget.percentage), 'h-3 rounded-full transition-all']" :style="{ width: Math.min(budget.percentage, 100) + '%' }"/>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">{{ budget.percentage }}% used</span>
                                <span :class="[budget.remaining >= 0 ? 'text-green-600' : 'text-red-600', 'font-semibold']">
                                    {{ budget.remaining >= 0 ? formatCurrency(budget.remaining) + " left" : "Over by " + formatCurrency(Math.abs(budget.remaining)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Transactions -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <Icon name="heroicons:arrow-path" class="w-4 h-4" />
                                    <span>{{ budget.transactionCount }} transactions</span>
                                </div>
                                <NuxtLink :to="`/budgets/${budget.id}`" class="text-indigo-600 hover:text-indigo-700 font-medium flex items-center space-x-1">
                                    <span>View Details</span>
                                    <Icon name="heroicons:arrow-right" class="w-4 h-4" />
                                </NuxtLink>
                            </div>
                        </div>

                        <!-- Daily Average (for monthly budgets) -->
                        <div v-if="budget.period === 'Monthly'" class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Daily average</span>
                                <span class="font-semibold text-gray-900">{{ formatCurrency(budget.spent / 30) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm mt-2">
                                <span class="text-gray-600">Projected end</span>
                                <span :class="[budget.percentage > 100 ? 'text-red-600' : 'text-gray-900', 'font-semibold']">{{ formatCurrency((budget.spent / 30) * 30) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredBudgets.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <Icon name="heroicons:chart-bar" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No budgets found</h3>
                <p class="text-gray-600 mb-6">Create your first budget to start tracking your spending</p>
                <NuxtLink to="/budgets/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                    <Icon name="heroicons:plus" class="w-5 h-5" />
                </NuxtLink>
            </div>

            <!-- Budget Tips -->
            <div class="mt-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-6 border border-indigo-100">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <Icon name="heroicons:light-bulb" class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Budget Tips</h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start space-x-2">
                                <Icon name="heroicons:check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                                <span>Follow the 50/30/20 rule: 50% needs, 30% wants, 20% savings</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <Icon name="heroicons:check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                                <span>Review and adjust your budgets monthly based on spending patterns</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <Icon name="heroicons:check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                                <span>Set alerts when you reach 80% of your budget to avoid overspending</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <Icon name="heroicons:check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                                <span>Start with realistic budgets and gradually reduce them as you build discipline</span>
                            </li>
                        </ul>
                    </div>
                </div>
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
            icon: "heroicons:shopping-bag",
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
            icon: "heroicons:truck",
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
            icon: "heroicons:film",
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
            icon: "heroicons:shopping-cart",
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
            icon: "heroicons:heart",
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
            icon: "heroicons:bolt",
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
            icon: "heroicons:academic-cap",
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
            icon: "heroicons:cake",
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
        return budgets.value.filter((b) => b.percentage >= 80).length;
    });

    const filteredBudgets = computed(() => {
        let filtered = budgets.value;

        // Search filter
        if (searchQuery.value) {
            filtered = filtered.filter((b) => b.category.toLowerCase().includes(searchQuery.value.toLowerCase()));
        }

        // Status filter
        if (filterStatus.value === "on-track") {
            filtered = filtered.filter((b) => b.percentage < 70);
        } else if (filterStatus.value === "warning") {
            filtered = filtered.filter((b) => b.percentage >= 70 && b.percentage < 100);
        } else if (filterStatus.value === "exceeded") {
            filtered = filtered.filter((b) => b.percentage >= 100);
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

    const getStatusClass = (percentage) => {
        if (percentage >= 100) return "bg-red-100 text-red-600";
        if (percentage >= 80) return "bg-yellow-100 text-yellow-600";
        return "bg-green-100 text-green-600";
    };

    const getStatusText = (percentage) => {
        if (percentage >= 100) return "Exceeded";
        if (percentage >= 80) return "Warning";
        return "On Track";
    };

    const getProgressColor = (percentage) => {
        if (percentage >= 100) return "bg-red-500";
        if (percentage >= 80) return "bg-yellow-500";
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
