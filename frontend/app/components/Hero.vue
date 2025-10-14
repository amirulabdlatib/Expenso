<template>
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-gray-50 pt-20 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="inline-block">
                        <span class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full text-sm font-medium"> Smart Financial Management </span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                        Take Control of Your
                        <span class="text-indigo-600"> Finances</span>
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed">Track expenses, manage budgets, and achieve your savings goals with Expenso. The modern way to manage your money across all your accounts.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <NuxtLink to="/register" class="group bg-indigo-600 text-white px-8 py-4 rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 font-medium text-center flex items-center justify-center space-x-2">
                            <span>Start Free Today</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </NuxtLink>
                        <a href="#how-it-works" class="border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-lg hover:bg-indigo-50 transition-colors font-medium text-center"> Learn More </a>
                    </div>
                    <div class="flex items-center space-x-8 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            <span>No credit card required</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                            <span>Free forever</span>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 transform hover:scale-105 transition-transform">
                        <div class="space-y-6">
                            <div class="flex items-center justify-between pb-4 border-b">
                                <h3 class="text-lg font-semibold text-gray-900">Total Balance</h3>
                                <span class="text-sm text-gray-500">October 2025</span>
                            </div>
                            <div>
                                <p class="text-4xl font-bold text-gray-900">RM 24,850.00</p>
                                <p class="text-green-600 text-sm mt-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m5 12 7-7 7 7" />
                                        <path d="M12 19V5" />
                                    </svg>
                                    +12.5% from last month
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Income</p>
                                    <p class="text-xl font-bold text-green-600">+RM 8,500</p>
                                </div>
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Expenses</p>
                                    <p class="text-xl font-bold text-red-600">-RM 3,200</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-indigo-600 text-white p-4 rounded-lg shadow-lg">
                        <p class="text-sm">Savings Goal</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <div class="w-24 h-2 bg-indigo-400 rounded-full overflow-hidden">
                                <div class="h-full bg-white rounded-full" style="width: 65%"></div>
                            </div>
                            <span class="text-sm font-semibold">65%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
