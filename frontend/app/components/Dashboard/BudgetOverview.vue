<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900">Budget Overview</h2>
            <NuxtLink to="/budgets" class="text-sm text-indigo-600 hover:text-indigo-700 font-light"> Manage → </NuxtLink>
        </div>

        <!-- Empty State -->
        <div v-if="!budgets || budgets.length === 0" class="flex flex-col items-center justify-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                <Icon name="heroicons:chart-bar" class="w-8 h-8 text-gray-400" />
            </div>
            <p class="text-gray-600 text-sm mb-3">No budgets set for this month</p>
            <NuxtLink to="/budgets/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                <Icon name="heroicons:plus" class="w-4 h-4" />
                <span>Create Budget</span>
            </NuxtLink>
        </div>

        <!-- Budget Items -->
        <div v-else class="space-y-6">
            <div v-for="budget in budgets" :key="budget.id">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-light text-sm text-gray-900">{{ budget.budget_category }}</span>
                    <span class="text-sm font-medium text-gray-900"> {{ formatCurrency(budget.spent) }} / {{ formatCurrency(budget.budget_amount) }} </span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div :class="getProgressColor(budget, currentMonth, currentYear)" class="h-full rounded-full transition-all duration-300" :style="{ width: Math.min(budget.percentage, 100) + '%' }" />
                </div>
                <div class="flex items-center justify-between mt-1">
                    <span :class="getStatusClass(budget, currentMonth, currentYear)" class="text-xs px-2 py-0.5 rounded-full">
                        {{ getStatusText(budget, currentMonth, currentYear) }}
                    </span>
                    <span class="text-xs text-gray-500">{{ budget.percentage }}% used</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        budgets: {
            type: Array,
            default: () => [],
        },
    });

    const { formatCurrency } = useCurrency();
    const { getStatusText, getStatusClass, getProgressColor } = useBudgetUtils();

    const now = new Date();
    const currentMonth = now.getMonth() + 1;
    const currentYear = now.getFullYear();
</script>
