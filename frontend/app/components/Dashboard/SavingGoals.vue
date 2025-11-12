<template>
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Active Savings Goals</h2>
            <NuxtLink to="/goals" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"> View All → </NuxtLink>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Goal Card -->
            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                <div class="text-4xl mb-4">🏖️</div>
                <h3 class="font-semibold text-gray-900 mb-2">Vacation Fund</h3>
                <p class="text-sm text-gray-600 mb-4">Target: RM 10,000</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Progress</span>
                        <span class="font-semibold text-indigo-600">65%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full" style="width: 65%" />
                    </div>
                    <p class="text-sm font-semibold text-gray-900">RM 6,500 saved</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                <div class="text-4xl mb-4">💻</div>
                <h3 class="font-semibold text-gray-900 mb-2">New Laptop</h3>
                <p class="text-sm text-gray-600 mb-4">Target: RM 5,000</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Progress</span>
                        <span class="font-semibold text-purple-600">42%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-600 rounded-full" style="width: 42%" />
                    </div>
                    <p class="text-sm font-semibold text-gray-900">RM 2,100 saved</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                <div class="text-4xl mb-4">🚗</div>
                <h3 class="font-semibold text-gray-900 mb-2">Car Down Payment</h3>
                <p class="text-sm text-gray-600 mb-4">Target: RM 15,000</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Progress</span>
                        <span class="font-semibold text-green-600">28%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-green-600 rounded-full" style="width: 28%" />
                    </div>
                    <p class="text-sm font-semibold text-gray-900">RM 4,200 saved</p>
                </div>
            </div>
        </div>
    </div>
</template>
