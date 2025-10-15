<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Profile Header -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg p-8 mb-6">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                    <!-- Profile Picture -->
                    <div class="relative">
                        <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center text-indigo-600 text-5xl font-bold shadow-lg">AS</div>
                        <button class="absolute bottom-0 right-0 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100 transition-colors">
                            <Icon name="heroicons:camera" class="w-5 h-5 text-gray-600" />
                        </button>
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-1 text-center md:text-left text-white">
                        <h1 class="text-3xl font-bold mb-2">{{ profile.firstName }} {{ profile.lastName }}</h1>
                        <p class="text-indigo-100 mb-4">{{ profile.email }}</p>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <Icon name="heroicons:calendar" class="w-4 h-4" />
                                <span>Joined {{ formatDate(profile.joinDate) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Icon name="heroicons:map-pin" class="w-4 h-4" />
                                <span>{{ profile.location }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <Icon name="heroicons:shield-check" class="w-4 h-4" />
                                <span>{{ profile.verified ? "Verified Account" : "Unverified" }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-center md:justify-start space-x-3 mt-6">
                            <NuxtLink to="/settings" class="px-6 py-2 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition-colors font-medium"> Edit Profile </NuxtLink>
                            <button class="px-6 py-2 bg-white bg-opacity-20 text-white rounded-lg hover:bg-opacity-30 transition-colors font-medium">Share Profile</button>
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
                                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(60000) }}</p>
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
                                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(45000) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <Icon name="heroicons:wallet" class="w-5 h-5 text-indigo-600" />
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Net Savings</p>
                                        <p class="text-lg font-bold text-gray-900">{{ formatCurrency(15000) }}</p>
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

                    <!-- Badges Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Achievements</h2>
                        <div class="grid grid-cols-3 gap-3">
                            <div v-for="badge in badges" :key="badge.id" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer" :class="badge.earned ? 'opacity-100' : 'opacity-40'">
                                <span class="text-3xl mb-2">{{ badge.icon }}</span>
                                <p class="text-xs text-center text-gray-600 font-medium">{{ badge.name }}</p>
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
                                <p class="text-2xl font-bold text-gray-900">142</p>
                                <p class="text-xs text-gray-600">Transactions</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <Icon name="heroicons:chart-bar" class="w-8 h-8 text-green-600 mx-auto mb-2" />
                                <p class="text-2xl font-bold text-gray-900">8</p>
                                <p class="text-xs text-gray-600">Budgets</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <Icon name="heroicons:flag" class="w-8 h-8 text-purple-600 mx-auto mb-2" />
                                <p class="text-2xl font-bold text-gray-900">5</p>
                                <p class="text-xs text-gray-600">Goals</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <Icon name="heroicons:building-library" class="w-8 h-8 text-blue-600 mx-auto mb-2" />
                                <p class="text-2xl font-bold text-gray-900">6</p>
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

                    <!-- Goals Progress -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Goals Progress</h2>
                            <NuxtLink to="/goals" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</NuxtLink>
                        </div>
                        <div class="space-y-4">
                            <div v-for="goal in goals" :key="goal.id" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">{{ goal.emoji }}</span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ goal.name }}</p>
                                            <p class="text-xs text-gray-500">{{ formatCurrency(goal.current) }} / {{ formatCurrency(goal.target) }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-indigo-600">{{ goal.progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div :style="{ width: goal.progress + '%' }" class="bg-indigo-600 h-2 rounded-full transition-all"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Connected Accounts -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Connected Accounts</h2>
                            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Manage</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="account in connectedAccounts" :key="account.id" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition-colors">
                                <div :style="{ backgroundColor: account.color }" class="w-10 h-10 rounded-lg flex items-center justify-center">
                                    <Icon :name="account.icon" class="w-5 h-5 text-white" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ account.name }}</p>
                                    <p class="text-xs text-gray-500">{{ account.type }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ formatCurrency(account.balance) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from "vue";

    useHead({
        title: "Profile - Expenso",
    });

    // Profile Data
    const profile = ref({
        firstName: "Ahmad",
        lastName: "Syafiq",
        email: "ahmad@email.com",
        joinDate: "2024-01-15",
        location: "Kuala Lumpur, Malaysia",
        verified: true,
        currency: "MYR",
        language: "English",
        theme: "Light",
        notifications: true,
    });

    const activityPeriod = ref("month");

    // Badges Data
    const badges = ref([
        { id: 1, name: "First Transaction", icon: "🎯", earned: true },
        { id: 2, name: "Budget Master", icon: "💰", earned: true },
        { id: 3, name: "Goal Achiever", icon: "🏆", earned: true },
        { id: 4, name: "Savings Streak", icon: "🔥", earned: true },
        { id: 5, name: "Big Spender", icon: "💸", earned: false },
        { id: 6, name: "Money Expert", icon: "📊", earned: false },
    ]);

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

    // Goals Data
    const goals = ref([
        { id: 1, name: "Vacation to Japan", emoji: "✈️", current: 10500, target: 15000, progress: 70 },
        { id: 2, name: "Emergency Fund", emoji: "🆘", current: 16000, target: 20000, progress: 80 },
        { id: 3, name: "New Laptop", emoji: "💻", current: 1500, target: 5000, progress: 30 },
    ]);

    // Connected Accounts
    const connectedAccounts = ref([
        { id: 1, name: "Maybank Savings", type: "Bank Account", icon: "heroicons:building-library", color: "#3B82F6", balance: 15420.5 },
        { id: 2, name: "CIMB Credit Card", type: "Credit Card", icon: "heroicons:credit-card", color: "#EF4444", balance: 2340.0 },
        { id: 3, name: "Cash Wallet", type: "Cash", icon: "heroicons:banknotes", color: "#10B981", balance: 850.0 },
        { id: 4, name: "Touch 'n Go", type: "E-Wallet", icon: "heroicons:device-phone-mobile", color: "#8B5CF6", balance: 320.5 },
    ]);

    // Methods
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat("en-MY", {
            style: "currency",
            currency: "MYR",
        }).format(amount);
    };

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString("en-MY", {
            month: "long",
            year: "numeric",
        });
    };

    const formatTimeAgo = (timestamp) => {
        const date = new Date(timestamp);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return "Just now";
        if (seconds < 3600) return `${Math.floor(seconds / 60)} minutes ago`;
        if (seconds < 86400) return `${Math.floor(seconds / 3600)} hours ago`;
        if (seconds < 604800) return `${Math.floor(seconds / 86400)} days ago`;
        return date.toLocaleDateString();
    };

    const getActivityIcon = (type) => {
        const icons = {
            expense: "heroicons:arrow-trending-down",
            income: "heroicons:arrow-trending-up",
            budget: "heroicons:chart-bar",
            goal: "heroicons:flag",
        };
        return icons[type] || "heroicons:document-text";
    };

    const getActivityColor = (type) => {
        const colors = {
            expense: "bg-red-500",
            income: "bg-green-500",
            budget: "bg-indigo-500",
            goal: "bg-purple-500",
        };
        return colors[type] || "bg-gray-500";
    };
</script>
