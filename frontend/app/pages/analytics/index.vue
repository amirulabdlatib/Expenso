<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Analytics</h1>
                        <p class="text-gray-600 mt-2">Insights into your financial health</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select v-model="selectedPeriod" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">This Quarter</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-linear-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:arrow-trending-up" class="w-8 h-8 opacity-80" />
                        <span class="text-xs bg-opacity-20 px-2 py-1 rounded-full">+12.5%</span>
                    </div>
                    <p class="text-sm opacity-80 mb-1">Total Income</p>
                    <p class="text-3xl font-bold">{{ formatCurrency(6750.0) }}</p>
                    <p class="text-xs opacity-80 mt-2">vs last period</p>
                </div>

                <div class="bg-linear-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:arrow-trending-down" class="w-8 h-8 opacity-80" />
                        <span class="text-xs bg-opacity-20 px-2 py-1 rounded-full">-8.3%</span>
                    </div>
                    <p class="text-sm opacity-80 mb-1">Total Expenses</p>
                    <p class="text-3xl font-bold">{{ formatCurrency(4596.4) }}</p>
                    <p class="text-xs opacity-80 mt-2">vs last period</p>
                </div>

                <div class="bg-linear-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:banknotes" class="w-8 h-8 opacity-80" />
                        <span class="text-xs bg-opacity-20 px-2 py-1 rounded-full">+32.1%</span>
                    </div>
                    <p class="text-sm opacity-80 mb-1">Net Savings</p>
                    <p class="text-3xl font-bold">{{ formatCurrency(2153.6) }}</p>
                    <p class="text-xs opacity-80 mt-2">vs last period</p>
                </div>

                <div class="bg-linear-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:chart-pie" class="w-8 h-8 opacity-80" />
                        <span class="text-xs bg-opacity-20 px-2 py-1 rounded-full">68%</span>
                    </div>
                    <p class="text-sm opacity-80 mb-1">Savings Rate</p>
                    <p class="text-3xl font-bold">32%</p>
                    <p class="text-xs opacity-80 mt-2">of income saved</p>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Income vs Expenses Chart -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Income vs Expenses</h2>
                        <select class="px-3 py-1 text-sm border border-gray-300 rounded-lg">
                            <option>Last 6 months</option>
                            <option>Last 12 months</option>
                        </select>
                    </div>
                    <div class="h-64 flex items-end justify-between space-x-2">
                        <div v-for="(month, index) in incomeExpenseData" :key="index" class="flex-1 flex flex-col items-center space-y-2">
                            <div class="w-full flex space-x-1">
                                <div class="flex-1 bg-green-500 rounded-t" :style="{ height: (month.income / 6000) * 200 + 'px' }" />
                                <div class="flex-1 bg-red-500 rounded-t" :style="{ height: (month.expense / 6000) * 200 + 'px' }" />
                            </div>
                            <span class="text-xs text-gray-600">{{ month.month }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-center space-x-6 mt-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded" />
                            <span class="text-sm text-gray-600">Income</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded" />
                            <span class="text-sm text-gray-600">Expenses</span>
                        </div>
                    </div>
                </div>

                <!-- Category Breakdown -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Spending by Category</h2>
                        <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</button>
                    </div>
                    <div class="space-y-4">
                        <div v-for="category in categoryData" :key="category.name" class="space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div :style="{ backgroundColor: category.color + '20' }" class="w-10 h-10 rounded-lg flex items-center justify-center">
                                        <Icon :name="category.icon" class="w-5 h-5" :style="{ color: category.color }" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ category.name }}</p>
                                        <p class="text-xs text-gray-500">{{ category.count }} transactions</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ formatCurrency(category.amount) }}</p>
                                    <p class="text-xs text-gray-500">{{ category.percentage }}%</p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div :style="{ width: category.percentage + '%', backgroundColor: category.color }" class="h-2 rounded-full" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Budget Progress -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Budget Progress</h2>
                    <div class="space-y-6">
                        <div v-for="budget in budgetData" :key="budget.category">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">{{ budget.category }}</span>
                                <span class="text-sm text-gray-600">{{ formatCurrency(budget.spent) }} / {{ formatCurrency(budget.limit) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div :class="[budget.percentage > 90 ? 'bg-red-500' : budget.percentage > 70 ? 'bg-yellow-500' : 'bg-green-500', 'h-2.5 rounded-full']" :style="{ width: budget.percentage + '%' }" />
                            </div>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-500">{{ budget.percentage }}% used</span>
                                <span class="text-xs" :class="[budget.remaining < 0 ? 'text-red-600' : 'text-green-600']">{{
                                    budget.remaining >= 0 ? formatCurrency(budget.remaining) + " left" : "Over by " + formatCurrency(Math.abs(budget.remaining))
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Expenses -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Top Expenses</h2>
                    <div class="space-y-4">
                        <div v-for="(expense, index) in topExpenses" :key="index" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <span class="text-lg font-bold text-gray-600">{{ index + 1 }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ expense.name }}</p>
                                    <p class="text-xs text-gray-500">{{ expense.category }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-red-600">{{ formatCurrency(expense.amount) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Accounts Overview -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Accounts Balance</h2>
                    <div class="space-y-4">
                        <div v-for="account in accountsBalance" :key="account.name" class="space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <Icon :name="account.icon" class="w-5 h-5" :style="{ color: account.color }" />
                                    <span class="text-sm font-medium text-gray-900">{{ account.name }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ formatCurrency(account.balance) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div :style="{ width: account.percentage + '%', backgroundColor: account.color }" class="h-1.5 rounded-full" />
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-900">Total Balance</span>
                                <span class="text-lg font-bold text-indigo-600">{{ formatCurrency(totalAccountBalance) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Trends -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Spending Trends -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Spending Trends</h2>
                    <div class="space-y-4">
                        <div v-for="trend in spendingTrends" :key="trend.category" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div :style="{ backgroundColor: trend.color + '20' }" class="w-12 h-12 rounded-lg flex items-center justify-center">
                                    <Icon :name="trend.icon" class="w-6 h-6" :style="{ color: trend.color }" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ trend.category }}</p>
                                    <p class="text-xs text-gray-500">{{ trend.change > 0 ? "Increased" : "Decreased" }} by {{ Math.abs(trend.change) }}%</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Icon :name="trend.change > 0 ? 'heroicons:arrow-trending-up' : 'heroicons:arrow-trending-down'" class="w-5 h-5" :class="trend.change > 0 ? 'text-red-500' : 'text-green-500'" />
                                <span :class="[trend.change > 0 ? 'text-red-600' : 'text-green-600', 'text-sm font-semibold']">{{ Math.abs(trend.change) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Goals -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Financial Goals</h2>
                        <NuxtLink to="/goals" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</NuxtLink>
                    </div>
                    <div class="space-y-4">
                        <div v-for="goal in financialGoals" :key="goal.name" class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl">{{ goal.emoji }}</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ goal.name }}</p>
                                        <p class="text-xs text-gray-500">Target: {{ formatCurrency(goal.target) }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-indigo-600">{{ goal.progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full" :style="{ width: goal.progress + '%' }" />
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">{{ formatCurrency(goal.current) }} saved</span>
                                <span class="text-xs text-gray-500">{{ formatCurrency(goal.target - goal.current) }} remaining</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Analytics - Expenso",
    });

    const selectedPeriod = ref("month");

    // Dummy Data
    const incomeExpenseData = ref([
        { month: "Jul", income: 5200, expense: 3800 },
        { month: "Aug", income: 5400, expense: 4100 },
        { month: "Sep", income: 5100, expense: 3900 },
        { month: "Oct", income: 5500, expense: 4300 },
        { month: "Nov", income: 5300, expense: 4000 },
        { month: "Dec", income: 5600, expense: 4200 },
    ]);

    const categoryData = ref([
        { name: "Food & Groceries", icon: "heroicons:shopping-bag", color: "#10B981", amount: 1250.5, percentage: 27, count: 45 },
        { name: "Transportation", icon: "heroicons:truck", color: "#3B82F6", amount: 890.0, percentage: 19, count: 32 },
        { name: "Entertainment", icon: "heroicons:film", color: "#F59E0B", amount: 650.3, percentage: 14, count: 28 },
        { name: "Shopping", icon: "heroicons:shopping-cart", color: "#EC4899", amount: 580.2, percentage: 13, count: 24 },
        { name: "Utilities", icon: "heroicons:bolt", color: "#8B5CF6", amount: 420.0, percentage: 9, count: 12 },
    ]);

    const budgetData = ref([
        { category: "Food", spent: 1250, limit: 1500, percentage: 83, remaining: 250 },
        { category: "Transport", spent: 890, limit: 800, percentage: 111, remaining: -90 },
        { category: "Entertainment", spent: 650, limit: 1000, percentage: 65, remaining: 350 },
        { category: "Shopping", spent: 580, limit: 600, percentage: 97, remaining: 20 },
    ]);

    const topExpenses = ref([
        { name: "Grocery Shopping", category: "Food", amount: 256.5 },
        { name: "Fuel Top-up", category: "Transport", amount: 180.0 },
        { name: "Electricity Bill", category: "Utilities", amount: 150.0 },
        { name: "Restaurant Dinner", category: "Food", amount: 145.8 },
        { name: "Movie Tickets", category: "Entertainment", amount: 120.0 },
    ]);

    const accountsBalance = ref([
        { name: "Maybank Savings", icon: "heroicons:building-library", color: "#3B82F6", balance: 15420.5, percentage: 43 },
        { name: "Emergency Fund", icon: "heroicons:shield-check", color: "#06B6D4", balance: 12000.0, percentage: 33 },
        { name: "Public Bank", icon: "heroicons:building-library", color: "#F59E0B", balance: 8750.0, percentage: 24 },
    ]);

    const spendingTrends = ref([
        { category: "Food & Groceries", icon: "heroicons:shopping-bag", color: "#10B981", change: 15 },
        { category: "Transportation", icon: "heroicons:truck", color: "#3B82F6", change: -8 },
        { category: "Entertainment", icon: "heroicons:film", color: "#F59E0B", change: 22 },
        { category: "Healthcare", icon: "heroicons:heart", color: "#EF4444", change: -12 },
    ]);

    const financialGoals = ref([
        { name: "Vacation Fund", emoji: "✈️", current: 3500, target: 5000, progress: 70 },
        { name: "Emergency Savings", emoji: "🆘", current: 8000, target: 10000, progress: 80 },
        { name: "New Laptop", emoji: "💻", current: 1200, target: 4000, progress: 30 },
    ]);

    // Computed
    const totalAccountBalance = computed(() => {
        return accountsBalance.value.reduce((sum, acc) => sum + acc.balance, 0);
    });

    // Methods
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat("en-MY", {
            style: "currency",
            currency: "MYR",
        }).format(amount);
    };
</script>
