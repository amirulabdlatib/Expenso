<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Financial Goals</h1>
                        <p class="text-gray-600 mt-2">Track your savings goals and achieve your dreams</p>
                    </div>
                    <NuxtLink to="/goals/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span class="font-medium">Create Goal</span>
                    </NuxtLink>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:flag" class="w-8 h-8 opacity-80" />
                    </div>
                    <p class="text-sm opacity-80 mb-1">Active Goals</p>
                    <p class="text-3xl font-bold">{{ activeGoals.length }}</p>
                    <p class="text-xs opacity-80 mt-2">{{ completedGoals.length }} completed</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:currency-dollar" class="w-8 h-8 opacity-80" />
                    </div>
                    <p class="text-sm opacity-80 mb-1">Total Target</p>
                    <p class="text-3xl font-bold">{{ formatCurrency(totalTarget) }}</p>
                    <p class="text-xs opacity-80 mt-2">Combined goals</p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:wallet" class="w-8 h-8 opacity-80" />
                    </div>
                    <p class="text-sm opacity-80 mb-1">Total Saved</p>
                    <p class="text-3xl font-bold">{{ formatCurrency(totalSaved) }}</p>
                    <p class="text-xs opacity-80 mt-2">{{ overallProgress }}% of target</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <Icon name="heroicons:clock" class="w-8 h-8 opacity-80" />
                    </div>
                    <p class="text-sm opacity-80 mb-1">Avg. Time Left</p>
                    <p class="text-3xl font-bold">{{ avgMonthsLeft }}</p>
                    <p class="text-xs opacity-80 mt-2">months remaining</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button @click="filterStatus = 'all'" :class="[filterStatus === 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            All Goals
                        </button>
                        <button
                            @click="filterStatus = 'active'"
                            :class="[filterStatus === 'active' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            Active
                        </button>
                        <button
                            @click="filterStatus = 'completed'"
                            :class="[filterStatus === 'completed' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            Completed
                        </button>
                        <button
                            @click="filterStatus = 'paused'"
                            :class="[filterStatus === 'paused' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200', 'px-4 py-2 rounded-lg transition-colors text-sm font-medium']">
                            Paused
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select v-model="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="progress">Sort by Progress</option>
                            <option value="deadline">Sort by Deadline</option>
                            <option value="amount">Sort by Amount</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Goals Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="goal in filteredGoals" :key="goal.id" class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all overflow-hidden">
                    <!-- Goal Header -->
                    <div :style="{ background: `linear-gradient(135deg, ${goal.color} 0%, ${goal.color}dd 100%)` }" class="p-6 text-white relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="text-4xl">{{ goal.emoji }}</div>
                                <div>
                                    <h3 class="font-semibold text-lg">{{ goal.name }}</h3>
                                    <p class="text-sm opacity-80">{{ goal.category }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                <NuxtLink :to="`/goals/edit/${goal.id}`" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                                    <Icon name="heroicons:pencil" class="w-4 h-4" />
                                </NuxtLink>
                                <button @click="deleteGoal(goal.id)" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                                    <Icon name="heroicons:trash" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Progress Circle -->
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-80 mb-1">Progress</p>
                                <p class="text-2xl font-bold">{{ goal.progress }}%</p>
                            </div>
                            <div class="w-20 h-20 relative">
                                <svg class="w-20 h-20 transform -rotate-90">
                                    <circle cx="40" cy="40" r="36" stroke="rgba(255,255,255,0.2)" stroke-width="8" fill="none" />
                                    <circle cx="40" cy="40" r="36" stroke="white" stroke-width="8" fill="none" :stroke-dasharray="226" :stroke-dashoffset="226 - (226 * goal.progress) / 100" stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <Icon v-if="goal.status === 'completed'" name="heroicons:check" class="w-8 h-8" />
                                    <span v-else class="text-xs font-bold">{{ goal.progress }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Goal Details -->
                    <div class="p-6 space-y-4">
                        <!-- Amount Info -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Current</span>
                                <span class="font-semibold text-gray-900">{{ formatCurrency(goal.current) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Target</span>
                                <span class="font-semibold text-gray-900">{{ formatCurrency(goal.target) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Remaining</span>
                                <span class="font-semibold text-indigo-600">{{ formatCurrency(goal.target - goal.current) }}</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div :style="{ width: goal.progress + '%', backgroundColor: goal.color }" class="h-2.5 rounded-full transition-all"></div>
                        </div>

                        <!-- Timeline -->
                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <Icon name="heroicons:calendar" class="w-4 h-4" />
                                    <span>Deadline</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ formatDate(goal.deadline) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <Icon name="heroicons:clock" class="w-4 h-4" />
                                    <span>Time Left</span>
                                </div>
                                <span :class="[goal.daysLeft < 30 ? 'text-red-600' : 'text-gray-900', 'font-semibold']">{{ goal.daysLeft }} days</span>
                            </div>
                            <div v-if="goal.monthlyTarget" class="flex items-center justify-between text-sm">
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <Icon name="heroicons:arrow-trending-up" class="w-4 h-4" />
                                    <span>Monthly Target</span>
                                </div>
                                <span class="font-semibold text-gray-900">{{ formatCurrency(goal.monthlyTarget) }}</span>
                            </div>
                        </div>

                        <!-- Status & Actions -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span :class="[getStatusClass(goal.status), 'px-3 py-1 text-xs font-medium rounded-full']">{{ goal.status === "active" ? "Active" : goal.status === "completed" ? "Completed" : "Paused" }}</span>
                                <div class="flex items-center space-x-2">
                                    <button @click="addContribution(goal.id)" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition-colors">Add Savings</button>
                                    <NuxtLink :to="`/goals/${goal.id}`" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-50 transition-colors">
                                        <Icon name="heroicons:arrow-right" class="w-4 h-4" />
                                    </NuxtLink>
                                </div>
                            </div>
                        </div>

                        <!-- Milestones -->
                        <div v-if="goal.milestones && goal.milestones.length > 0" class="pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-2">Milestones</p>
                            <div class="flex items-center space-x-2">
                                <div v-for="(milestone, index) in goal.milestones" :key="index" :class="[goal.progress >= milestone ? 'bg-indigo-600' : 'bg-gray-200', 'flex-1 h-2 rounded-full']"></div>
                            </div>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-500">{{ goal.milestones.filter((m) => goal.progress >= m).length }}/{{ goal.milestones.length }} completed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredGoals.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <Icon name="heroicons:flag" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No goals found</h3>
                <p class="text-gray-600 mb-6">Start your financial journey by creating your first goal</p>
                <NuxtLink to="/goals/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                    <Icon name="heroicons:plus" class="w-5 h-5" />
                    <span>Create Goal</span>
                </NuxtLink>
            </div>

            <!-- Goal Ideas Section -->
            <div class="mt-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border border-indigo-100">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <Icon name="heroicons:sparkles" class="w-6 h-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Popular Goal Ideas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div v-for="idea in goalIdeas" :key="idea.name" class="bg-white rounded-lg p-4 border border-indigo-100 hover:border-indigo-300 transition-colors cursor-pointer">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="text-2xl">{{ idea.emoji }}</span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ idea.name }}</p>
                                        <p class="text-xs text-gray-500">{{ idea.timeframe }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600">{{ idea.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";

    // State
    const filterStatus = ref("all");
    const sortBy = ref("progress");

    // Dummy Data
    const goals = ref([
        {
            id: 1,
            name: "Vacation to Japan",
            emoji: "✈️",
            category: "Travel",
            color: "#3B82F6",
            target: 15000.0,
            current: 10500.0,
            progress: 70,
            deadline: "2025-12-31",
            daysLeft: 260,
            monthlyTarget: 642.86,
            status: "active",
            milestones: [25, 50, 75, 100],
        },
        {
            id: 2,
            name: "Emergency Fund",
            emoji: "🆘",
            category: "Safety Net",
            color: "#EF4444",
            target: 20000.0,
            current: 16000.0,
            progress: 80,
            deadline: "2026-06-30",
            daysLeft: 532,
            monthlyTarget: 228.07,
            status: "active",
            milestones: [25, 50, 75, 100],
        },
        {
            id: 3,
            name: "New Laptop",
            emoji: "💻",
            category: "Technology",
            color: "#8B5CF6",
            target: 5000.0,
            current: 1500.0,
            progress: 30,
            deadline: "2025-08-01",
            daysLeft: 168,
            monthlyTarget: 636.36,
            status: "active",
            milestones: [25, 50, 75, 100],
        },
        {
            id: 4,
            name: "Wedding Fund",
            emoji: "💍",
            category: "Life Event",
            color: "#EC4899",
            target: 50000.0,
            current: 23000.0,
            progress: 46,
            deadline: "2026-12-31",
            daysLeft: 806,
            monthlyTarget: 1022.73,
            status: "active",
            milestones: [25, 50, 75, 100],
        },
        {
            id: 5,
            name: "Car Down Payment",
            emoji: "🚗",
            category: "Vehicle",
            color: "#F59E0B",
            target: 30000.0,
            current: 18000.0,
            progress: 60,
            deadline: "2026-03-31",
            daysLeft: 532,
            monthlyTarget: 682.76,
            status: "active",
            milestones: [25, 50, 75, 100],
        },
        {
            id: 6,
            name: "Home Renovation",
            emoji: "🏠",
            category: "Home Improvement",
            color: "#10B981",
            target: 25000.0,
            current: 8500.0,
            progress: 34,
            deadline: "2025-11-30",
            daysLeft: 230,
            monthlyTarget: 2176.47,
            status: "paused",
            milestones: [25, 50, 75, 100],
        },
        {
            id: 7,
            name: "iPhone 16 Pro",
            emoji: "📱",
            category: "Technology",
            color: "#06B6D4",
            target: 6000.0,
            current: 6000.0,
            progress: 100,
            deadline: "2025-03-15",
            daysLeft: 0,
            monthlyTarget: 0,
            status: "completed",
            milestones: [25, 50, 75, 100],
        },
    ]);

    const goalIdeas = ref([
        { name: "Emergency Fund", emoji: "🆘", timeframe: "6-12 months", description: "3-6 months of expenses for unexpected situations" },
        { name: "Vacation Fund", emoji: "✈️", timeframe: "6-12 months", description: "Save for your dream holiday destination" },
        { name: "New Car", emoji: "🚗", timeframe: "1-2 years", description: "Down payment or full payment for a vehicle" },
        { name: "Home Down Payment", emoji: "🏠", timeframe: "2-5 years", description: "Save for your first home or property investment" },
        { name: "Education Fund", emoji: "🎓", timeframe: "1-3 years", description: "Tuition fees or professional development courses" },
        { name: "Wedding Fund", emoji: "💍", timeframe: "1-2 years", description: "Plan and save for your special day" },
    ]);

    // Computed
    const activeGoals = computed(() => {
        return goals.value.filter((g) => g.status === "active");
    });

    const completedGoals = computed(() => {
        return goals.value.filter((g) => g.status === "completed");
    });

    const totalTarget = computed(() => {
        return goals.value.reduce((sum, g) => sum + g.target, 0);
    });

    const totalSaved = computed(() => {
        return goals.value.reduce((sum, g) => sum + g.current, 0);
    });

    const overallProgress = computed(() => {
        return totalTarget.value > 0 ? Math.round((totalSaved.value / totalTarget.value) * 100) : 0;
    });

    const avgMonthsLeft = computed(() => {
        const activeGoalsOnly = goals.value.filter((g) => g.status === "active");
        if (activeGoalsOnly.length === 0) return 0;
        const totalDays = activeGoalsOnly.reduce((sum, g) => sum + g.daysLeft, 0);
        return Math.round(totalDays / activeGoalsOnly.length / 30);
    });

    const filteredGoals = computed(() => {
        let filtered = goals.value;

        // Status filter
        if (filterStatus.value === "active") {
            filtered = filtered.filter((g) => g.status === "active");
        } else if (filterStatus.value === "completed") {
            filtered = filtered.filter((g) => g.status === "completed");
        } else if (filterStatus.value === "paused") {
            filtered = filtered.filter((g) => g.status === "paused");
        }

        // Sort
        if (sortBy.value === "progress") {
            filtered = [...filtered].sort((a, b) => b.progress - a.progress);
        } else if (sortBy.value === "deadline") {
            filtered = [...filtered].sort((a, b) => a.daysLeft - b.daysLeft);
        } else if (sortBy.value === "amount") {
            filtered = [...filtered].sort((a, b) => b.target - a.target);
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

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString("en-MY", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    };

    const getStatusClass = (status) => {
        if (status === "completed") return "bg-green-100 text-green-600";
        if (status === "paused") return "bg-gray-100 text-gray-600";
        return "bg-blue-100 text-blue-600";
    };

    const deleteGoal = (id) => {
        if (confirm("Are you sure you want to delete this goal?")) {
            const index = goals.value.findIndex((g) => g.id === id);
            if (index !== -1) {
                goals.value.splice(index, 1);
            }
        }
    };

    const addContribution = (id) => {
        // This would open a modal or navigate to add contribution page
        alert(`Add contribution to goal ${id}`);
    };
</script>
