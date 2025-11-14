<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Profile Header - Minimalist Design -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
                <div class="flex flex-col md:flex-row items-start space-y-6 md:space-y-0 md:space-x-8">
                    <!-- Profile Picture -->
                    <div class="relative group">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 text-3xl font-semibold border-2 border-gray-200">AS</div>
                        <button class="absolute bottom-0 right-0 w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                            <Icon name="heroicons:camera" class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1">
                        <div class="mb-6">
                            <h1 class="text-2xl font-semibold text-gray-900 mb-1">{{ user.name }}</h1>
                            <p class="text-sm text-gray-500">{{ user.email }}</p>
                        </div>

                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center gap-6 mb-6 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <Icon name="heroicons:calendar" class="w-4 h-4 text-gray-400" />
                                <span>Joined {{ formatMonthYearOnly(user.created_at) }}</span>
                            </div>
                            <div v-if="isVerified" class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span>{{ isVerified }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3">
                            <NuxtLink to="/settings" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                <Icon name="heroicons:pencil-square" class="w-4 h-4" />
                                Edit Profile
                            </NuxtLink>
                            <button class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                <Icon name="heroicons:share" class="w-4 h-4" />
                                Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Stats Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Financial Stats</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <Icon name="heroicons:arrow-trending-up" class="w-5 h-5 text-green-600" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Total Income</p>
                                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(Number(data?.total_income) ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <Icon name="heroicons:arrow-trending-down" class="w-5 h-5 text-red-600" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Total Expenses</p>
                                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(Number(data?.total_expenses) ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <Icon name="heroicons:wallet" class="w-5 h-5 text-indigo-600" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Net Balance</p>
                                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(Number(data?.net_balance) ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <Icon name="heroicons:chart-pie" class="w-5 h-5 text-purple-600" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Savings Rate</p>
                                        <p class="text-lg font-bold text-gray-900">25%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Preferences</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Currency</span>
                                <span class="font-semibold text-gray-900">{{ profile.currency }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Language</span>
                                <span class="font-semibold text-gray-900">{{ profile.language }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Theme</span>
                                <span class="font-semibold text-gray-900">{{ profile.theme }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Notifications</span>
                                <span class="font-semibold text-gray-900">{{ profile.notifications ? "Enabled" : "Disabled" }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Activity Overview -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900">Activity Overview</h2>
                            <select v-model="activityPeriod" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <Icon name="heroicons:document-text" class="w-8 h-8 text-indigo-600 mx-auto mb-2" />
                                <p class="text-2xl font-bold text-gray-900">{{ data?.total_transactions ?? 0 }}</p>
                                <p class="text-xs text-gray-600">Transactions</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <Icon name="heroicons:building-library" class="w-8 h-8 text-blue-600 mx-auto mb-2" />
                                <p class="text-2xl font-bold text-gray-900">{{ data?.total_accounts ?? 0 }}</p>
                                <p class="text-xs text-gray-600">Accounts</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
                        <div class="space-y-4">
                            <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                <div :class="[getActivityColor(activity.type), 'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0']">
                                    <Icon :name="getActivityIcon(activity.type)" class="w-5 h-5 text-white" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ activity.description }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ formatTimeAgo(activity.timestamp) }}</p>
                                </div>
                                <span v-if="activity.amount" :class="[activity.type === 'income' ? 'text-green-600' : 'text-red-600', 'text-sm font-semibold whitespace-nowrap']">
                                    {{ activity.type === "income" ? "+" : "-" }}{{ formatCurrency(activity.amount) }}
                                </span>
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
        title: "Profile - Expenso",
    });

    const { formatMonthYearOnly, formatTimeAgo } = useUtils();
    const { getActivityColor, getActivityIcon } = useActivity();
    const { formatCurrency } = useCurrency();
    const { user } = useSanctumAuth();

    const { data, status, error, refresh } = await useSanctumFetch("api/profile");

    const isVerified = computed(() => (user?.isVerified ? "Verified Account" : ""));

    // Profile Data
    const profile = ref({
        currency: "MYR",
        language: "English",
        theme: "Light",
        notifications: true,
    });

    const activityPeriod = ref("month");

    // Recent Activities
    const recentActivities = ref([
        {
            id: 1,
            type: "expense",
            title: "Grocery Shopping",
            description: "Food & Groceries • Cash Wallet",
            timestamp: "2025-01-15T10:30:00",
            amount: 156.5,
        },
        {
            id: 2,
            type: "income",
            title: "Salary Payment",
            description: "Salary • Maybank Savings",
            timestamp: "2025-01-15T09:00:00",
            amount: 5000.0,
        },
        {
            id: 3,
            type: "budget",
            title: "Budget Created",
            description: "Food & Groceries budget set to RM1500",
            timestamp: "2025-01-14T15:20:00",
            amount: null,
        },
        {
            id: 4,
            type: "goal",
            title: "Goal Milestone Reached",
            description: "Vacation Fund reached 70% of target",
            timestamp: "2025-01-14T12:00:00",
            amount: null,
        },
        {
            id: 5,
            type: "expense",
            title: "Netflix Subscription",
            description: "Entertainment • CIMB Credit Card",
            timestamp: "2025-01-13T08:00:00",
            amount: 55.0,
        },
    ]);
</script>
