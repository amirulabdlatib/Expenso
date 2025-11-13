<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Budget Overview</h2>
            <NuxtLink to="/budgets" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"> Manage → </NuxtLink>
        </div>

        <div class="space-y-6">
            <!-- Budget Item -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">🍔</span>
                        <span class="font-medium text-gray-900">Food</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">RM 450 / RM 500</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-yellow-500 rounded-full" style="width: 90%" />
                </div>
                <p class="text-xs text-yellow-600 mt-1">⚠️ 90% used</p>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">🚗</span>
                        <span class="font-medium text-gray-900">Transport</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">RM 180 / RM 300</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" style="width: 60%" />
                </div>
                <p class="text-xs text-green-600 mt-1">✓ On track</p>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">🛒</span>
                        <span class="font-medium text-gray-900">Shopping</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">RM 245 / RM 400</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: 61%" />
                </div>
                <p class="text-xs text-blue-600 mt-1">✓ Good progress</p>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">🎬</span>
                        <span class="font-medium text-gray-900">Entertainment</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">RM 95 / RM 200</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full" style="width: 47%" />
                </div>
                <p class="text-xs text-indigo-600 mt-1">✓ Under budget</p>
            </div>
        </div>
    </div>
</template>
