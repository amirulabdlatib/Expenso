<template>
    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
        <div class="space-y-4">
            <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                <div :class="[getActivityColor(activity.type), 'w-10 h-10 rounded-lg flex items-center justify-center shrink-0']">
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
</template>

<script setup>
    const { getActivityColor, getActivityIcon } = useActivity();
    const { formatCurrency } = useCurrency();
    const { formatTimeAgo } = useUtils();

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
