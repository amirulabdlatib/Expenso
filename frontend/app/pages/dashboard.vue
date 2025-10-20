<template>
    <div>
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-2">Welcome back, Ahmad! Here's your financial overview.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Balance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                            <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                            <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Total Balance</p>
                <p class="text-2xl font-bold text-gray-900">RM 24,850.00</p>
                <p class="text-sm text-green-600 mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m5 12 7-7 7 7" />
                        <path d="M12 19V5" />
                    </svg>
                    +12.5% from last month
                </p>
            </div>

            <!-- Income -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20" />
                            <path d="m5 9 7-7 7 7" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Monthly Income</p>
                <p class="text-2xl font-bold text-gray-900">+RM 8,500</p>
                <p class="text-sm text-gray-500 mt-2">October 2025</p>
            </div>

            <!-- Expenses -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20" />
                            <path d="m19 15-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Monthly Expenses</p>
                <p class="text-2xl font-bold text-gray-900">-RM 3,200</p>
                <p class="text-sm text-gray-500 mt-2">October 2025</p>
            </div>

            <!-- Savings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="12" r="6" />
                            <circle cx="12" cy="12" r="2" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Savings Goals</p>
                <p class="text-2xl font-bold text-gray-900">RM 15,000</p>
                <div class="flex items-center space-x-2 mt-2">
                    <div class="flex-1 h-2 bg-purple-100 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-600 rounded-full" style="width: 65%"/>
                    </div>
                    <span class="text-sm text-purple-600 font-semibold">65%</span>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Transactions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Transactions -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent Transactions</h2>
                    <NuxtLink to="/transactions" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"> View All → </NuxtLink>
                </div>

                <div class="space-y-4">
                    <!-- Transaction Item -->
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center text-2xl">☕</div>
                            <div>
                                <p class="font-medium text-gray-900">Starbucks Coffee</p>
                                <p class="text-sm text-gray-500">Food & Dining • Today, 10:30 AM</p>
                            </div>
                        </div>
                        <p class="text-lg font-semibold text-red-600">-RM 15.00</p>
                    </div>

                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl">🚗</div>
                            <div>
                                <p class="font-medium text-gray-900">Petrol Station</p>
                                <p class="text-sm text-gray-500">Transport • Yesterday, 8:00 PM</p>
                            </div>
                        </div>
                        <p class="text-lg font-semibold text-red-600">-RM 80.00</p>
                    </div>

                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-2xl">💰</div>
                            <div>
                                <p class="font-medium text-gray-900">Monthly Salary</p>
                                <p class="text-sm text-gray-500">Income • Oct 1, 2025</p>
                            </div>
                        </div>
                        <p class="text-lg font-semibold text-green-600">+RM 8,500.00</p>
                    </div>

                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-2xl">🛒</div>
                            <div>
                                <p class="font-medium text-gray-900">Grocery Shopping</p>
                                <p class="text-sm text-gray-500">Shopping • Oct 12, 2025</p>
                            </div>
                        </div>
                        <p class="text-lg font-semibold text-red-600">-RM 245.50</p>
                    </div>

                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-2xl">📱</div>
                            <div>
                                <p class="font-medium text-gray-900">Phone Bill</p>
                                <p class="text-sm text-gray-500">Bills • Oct 10, 2025</p>
                            </div>
                        </div>
                        <p class="text-lg font-semibold text-red-600">-RM 120.00</p>
                    </div>
                </div>
            </div>

            <!-- Budget Overview -->
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
                            <div class="h-full bg-yellow-500 rounded-full" style="width: 90%"/>
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
                            <div class="h-full bg-green-500 rounded-full" style="width: 60%"/>
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
                            <div class="h-full bg-blue-500 rounded-full" style="width: 61%"/>
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
                            <div class="h-full bg-indigo-500 rounded-full" style="width: 47%"/>
                        </div>
                        <p class="text-xs text-indigo-600 mt-1">✓ Under budget</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Savings Goals -->
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
                            <div class="h-full bg-indigo-600 rounded-full" style="width: 65%"/>
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
                            <div class="h-full bg-purple-600 rounded-full" style="width: 42%"/>
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
                            <div class="h-full bg-green-600 rounded-full" style="width: 28%"/>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">RM 4,200 saved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    definePageMeta({
        layout: "default",
        middleware: ["sanctum:auth"],
    });

    useHead({
        title: "Dashboard - Expenso",
    });
</script>
