<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
                        <p class="text-gray-600 mt-2">Track all your income and expenses</p>
                    </div>
                    <NuxtLink to="/transactions/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span class="font-medium">Add Transaction</span>
                    </NuxtLink>
                </div>
            </div>

            <!-- Loading State (Initial Load Only) -->
            <div v-if="status === 'pending' && !transactionsData" class="flex items-center justify-center py-20">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Loading transactions...</p>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <Icon name="heroicons:exclamation-triangle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-red-900 mb-2">Failed to Load Transactions</h3>
                <p class="text-red-700 mb-4">{{ error.statusMessage || "An error occurred while fetching transactions." }}</p>
                <button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors" @click="refresh()">Try Again</button>
            </div>

            <!-- Success State -->
            <template v-else-if="transactionsData">
                <!-- 404 Not Found - Page out of bounds -->
                <div v-if="transactionsData.pagination && transactionsData.pagination.current_page > transactionsData.pagination.last_page" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <Icon name="heroicons:exclamation-triangle" class="w-20 h-20 text-yellow-500 mx-auto mb-4" />
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">404 - Page Not Found</h3>
                    <p class="text-gray-600 mb-6">The page you're looking for doesn't exist. There are only {{ transactionsData.pagination.last_page }} pages available.</p>
                    <button class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors" @click="changePage(1)">
                        <Icon name="heroicons:arrow-left" class="w-5 h-5" />
                        <span>Go to First Page</span>
                    </button>
                </div>

                <!-- Empty State - No Transactions at All -->
                <div v-else-if="(!transactionsData.transactions || transactionsData.transactions.length === 0) && !hasActiveFilters && !isClearing" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <Icon name="heroicons:banknotes" class="w-20 h-20 text-gray-300 mx-auto mb-4" />
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Transactions Yet</h3>
                    <p class="text-gray-600 mb-6">Get started by adding your first income or expense transaction.</p>
                    <NuxtLink to="/transactions/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                        <span>Add Your First Transaction</span>
                    </NuxtLink>
                </div>

                <!-- Transactions Exist or Search Results -->
                <template v-else>
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="p-3 bg-green-100 rounded-xl">
                                    <Icon name="heroicons:arrow-trending-up" class="w-6 h-6 text-green-600" />
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Income</p>
                            <p class="text-lg font-bold text-gray-900">{{ formatCurrency(transactionsData.total_income) }}</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="p-3 bg-red-100 rounded-xl">
                                    <Icon name="heroicons:arrow-trending-down" class="w-6 h-6 text-red-600" />
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Expenses</p>
                            <p class="text-lg font-bold text-gray-900">{{ formatCurrency(transactionsData.total_expenses) }}</p>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="p-3 bg-indigo-100 rounded-xl">
                                    <Icon name="heroicons:banknotes" class="w-6 h-6 text-indigo-600" />
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Net Balance</p>
                            <p :class="['text-lg font-bold', transactionsData.total_income - transactionsData.total_expenses >= 0 ? 'text-green-600' : 'text-red-600']">
                                {{ formatCurrency(transactionsData.total_income - transactionsData.total_expenses) }}
                            </p>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="p-3 bg-purple-100 rounded-xl">
                                    <Icon name="heroicons:calculator" class="w-6 h-6 text-purple-600" />
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Transactions</p>
                            <p class="text-lg font-bold text-gray-900">{{ transactionsData.pagination.total }}</p>
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <!-- Search Bar - Full Width -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search by transaction name..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    @input="handleSearch" />
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-5"></div>

                        <!-- Filter Label -->
                        <div class="mb-3">
                            <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                                <Icon name="heroicons:funnel" class="w-4 h-4 mr-2" />
                                Filters
                            </h3>
                        </div>

                        <!-- Filter Dropdowns - 4 Column Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                            <!-- Account Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">Account</label>
                                <div class="relative">
                                    <Icon name="heroicons:wallet" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                    <select
                                        v-model="accountName"
                                        class="w-full pl-9 pr-8 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none bg-white cursor-pointer transition-all text-sm"
                                        @change="handleAccountNameFilter">
                                        <option value="all">All Accounts</option>
                                        <option v-for="account in accounts" :key="account" :value="account">{{ account }}</option>
                                    </select>
                                    <Icon name="heroicons:chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                </div>
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">Type</label>
                                <div class="relative">
                                    <Icon name="heroicons:tag" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                    <select
                                        v-model="transactionFilterType"
                                        class="w-full pl-9 pr-8 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none bg-white cursor-pointer transition-all text-sm"
                                        @change="handletransactionFilterType">
                                        <option value="all">All Types</option>
                                        <option value="income">Income</option>
                                        <option value="expense">Expense</option>
                                        <option value="transfer">Transfer</option>
                                    </select>
                                    <Icon name="heroicons:chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">Category</label>
                                <div class="relative">
                                    <Icon name="heroicons:squares-2x2" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                    <select
                                        v-model="categoryName"
                                        class="w-full pl-9 pr-8 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none bg-white cursor-pointer transition-all text-sm"
                                        @change="handleCategoryNameFilter">
                                        <option value="all">All Categories</option>
                                        <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                                    </select>
                                    <Icon name="heroicons:chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                </div>
                            </div>

                            <!-- Sort Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">Sort By</label>
                                <div class="relative">
                                    <Icon name="heroicons:bars-arrow-down" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                    <select
                                        v-model="sortBy"
                                        class="w-full pl-9 pr-8 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none bg-white cursor-pointer transition-all text-sm"
                                        @change="handleSort">
                                        <option value="date-desc">Latest First</option>
                                        <option value="date-asc">Oldest First</option>
                                        <option value="amount-desc">Highest Amount</option>
                                        <option value="amount-asc">Lowest Amount</option>
                                    </select>
                                    <Icon name="heroicons:chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none" />
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-5"></div>

                        <!-- Quick Filters -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                                    <Icon name="heroicons:clock" class="w-4 h-4 mr-2" />
                                    Quick Filters
                                </h3>
                                <button v-if="hasActiveFilters" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center space-x-1 transition-colors" @click="clearSearch">
                                    <Icon name="heroicons:x-circle" class="w-4 h-4" />
                                    <span>Clear All</span>
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    :class="['px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200', quickFilter === 'today' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
                                    @click="handleQuickFilter('today')">
                                    <Icon name="heroicons:calendar" class="w-4 h-4 inline mr-1.5" />
                                    Today
                                </button>
                                <button
                                    :class="['px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200', quickFilter === 'week' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
                                    @click="handleQuickFilter('week')">
                                    <Icon name="heroicons:calendar-days" class="w-4 h-4 inline mr-1.5" />
                                    This Week
                                </button>
                                <button
                                    :class="['px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200', quickFilter === 'month' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
                                    @click="handleQuickFilter('month')">
                                    <Icon name="heroicons:calendar" class="w-4 h-4 inline mr-1.5" />
                                    This Month
                                </button>
                                <button
                                    :class="['px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200', quickFilter === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
                                    @click="handleQuickFilter('all')">
                                    <Icon name="heroicons:globe-alt" class="w-4 h-4 inline mr-1.5" />
                                    All Time
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Table with Loading Overlay -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
                        <!-- Loading Overlay -->
                        <div v-if="isSearching || isClearing" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 backdrop-blur-sm">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600 mx-auto mb-3"></div>
                                <p class="text-sm font-medium text-gray-700">{{ isClearing ? "Clearing filters..." : "Searching..." }}</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <Icon name="heroicons:calendar" class="w-4 h-4" />
                                                <span>Date & Time</span>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <Icon name="heroicons:document-text" class="w-4 h-4" />
                                                <span>Name</span>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <Icon name="heroicons:squares-2x2" class="w-4 h-4" />
                                                <span>Category</span>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <Icon name="heroicons:wallet" class="w-4 h-4" />
                                                <span>Account</span>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center justify-center space-x-1">
                                                <Icon name="heroicons:tag" class="w-4 h-4" />
                                                <span>Type</span>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center justify-end space-x-1">
                                                <Icon name="heroicons:currency-dollar" class="w-4 h-4" />
                                                <span>Amount</span>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center justify-center space-x-1">
                                                <Icon name="heroicons:cog-6-tooth" class="w-4 h-4" />
                                                <span>Actions</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <!-- No Search Results Row -->
                                    <tr v-if="!transactionsData.transactions || transactionsData.transactions.length === 0">
                                        <td colspan="7" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                    <Icon name="heroicons:magnifying-glass-circle" class="w-12 h-12 text-gray-400" />
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Results Found</h3>
                                                <p class="text-gray-600 mb-6 max-w-md">
                                                    No transactions match your current filters
                                                    <span v-if="searchQuery" class="font-medium"> for "{{ searchQuery }}"</span>
                                                </p>
                                                <button class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm" @click="clearSearch">
                                                    <Icon name="heroicons:x-mark" class="w-4 h-4" />
                                                    <span>Clear All Filters</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Transaction Rows -->
                                    <tr v-for="transaction in transactionsData.transactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors group">
                                        <!-- Date & Time -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div class="shrink-0">
                                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                                        <Icon name="heroicons:calendar" class="w-5 h-5 text-gray-600 group-hover:text-indigo-600" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ formatDate(transaction.transaction_date) }}</div>
                                                    <div class="text-xs text-gray-500">{{ formatTime(transaction.transaction_date) }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Name -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 max-w-xs truncate">
                                                {{ transaction.name }}
                                            </div>
                                        </td>

                                        <!-- Category -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div v-if="transaction.category" class="flex items-center space-x-2">
                                                <div :style="{ backgroundColor: transaction.category.color + '20' }" class="w-9 h-9 rounded-lg flex items-center justify-center shadow-sm">
                                                    <Icon :name="transaction.category.icon" class="w-5 h-5" :style="{ color: transaction.category.color }" />
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ transaction.category.name }}</span>
                                            </div>

                                            <div v-else class="flex items-center space-x-2">
                                                <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-blue-100 shadow-sm">
                                                    <Icon name="heroicons:arrow-path" class="w-5 h-5 text-blue-600" />
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">Transfer</span>
                                            </div>
                                        </td>

                                        <!-- Account -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <Icon :name="transaction.account.icon" class="w-5 h-5 text-gray-500" />
                                                <span class="text-sm font-medium text-gray-900">{{ transaction.account.name }}</span>
                                            </div>
                                        </td>

                                        <!-- Type -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                v-if="transaction.category"
                                                :class="[
                                                    transaction.category.type === 'income' ? 'bg-green-100 text-green-700 ring-1 ring-green-600/20' : 'bg-red-100 text-red-700 ring-1 ring-red-600/20',
                                                    'inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full',
                                                ]">
                                                <Icon :name="transaction.category.type === 'income' ? 'heroicons:arrow-up' : 'heroicons:arrow-down'" class="w-3 h-3 mr-1" />
                                                {{ transaction.category.type === "income" ? "Income" : "Expense" }}
                                            </span>

                                            <span v-else class="inline-flex items-center bg-blue-100 text-blue-700 ring-1 ring-blue-600/20 px-3 py-1 text-xs font-semibold rounded-full">
                                                <Icon name="heroicons:arrow-path" class="w-3 h-3 mr-1" />
                                                Transfer
                                            </span>
                                        </td>

                                        <!-- Amount -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span v-if="transaction.category" :class="[transaction.category.type === 'income' ? 'text-green-700' : 'text-red-700', 'text-sm font-bold']">
                                                {{ transaction.category.type === "income" ? "+" : "-" }}{{ formatCurrency(transaction.category.type === "income" ? transaction.credit : transaction.debit) }}
                                            </span>

                                            <span v-else class="text-blue-700 text-sm font-bold">
                                                {{ formatCurrency(transaction.debit || transaction.credit || 0) }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-1">
                                                <NuxtLink :to="`/transactions/${transaction.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors" title="View Details">
                                                    <Icon name="heroicons:eye" class="w-5 h-5" />
                                                </NuxtLink>
                                                <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" :disabled="isDeleting" title="Delete Transaction" @click="deleteTransaction(transaction.id)">
                                                    <Icon name="heroicons:trash" class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-gray-600">
                                    Showing <span class="font-medium text-gray-900">{{ transactionsData.pagination.from }}</span> to <span class="font-medium text-gray-900">{{ transactionsData.pagination.to }}</span> of
                                    <span class="font-medium text-gray-900">{{ transactionsData.pagination.total }}</span> entries
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                        :disabled="transactionsData.pagination.current_page === 1"
                                        @click="changePage(transactionsData.pagination.current_page - 1)">
                                        <Icon name="heroicons:chevron-left" class="w-4 h-4 inline mr-1" />
                                        Previous
                                    </button>

                                    <!-- Page numbers -->
                                    <template v-for="pageNumber in getPageNumbers" :key="pageNumber">
                                        <button v-if="pageNumber === '...'" class="px-3 py-2 text-gray-600 text-sm" disabled>
                                            {{ pageNumber }}
                                        </button>
                                        <button
                                            v-else
                                            class="px-4 py-2 border rounded-lg text-sm font-medium transition-colors"
                                            :class="{
                                                'border-indigo-600 bg-indigo-600 text-white shadow-sm': pageNumber === transactionsData.pagination.current_page,
                                                'border-gray-300 text-gray-700 hover:bg-gray-100': pageNumber !== transactionsData.pagination.current_page,
                                            }"
                                            @click="changePage(pageNumber)">
                                            {{ pageNumber }}
                                        </button>
                                    </template>

                                    <button
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                        :disabled="transactionsData.pagination.current_page === transactionsData.pagination.last_page"
                                        @click="changePage(transactionsData.pagination.current_page + 1)">
                                        Next
                                        <Icon name="heroicons:chevron-right" class="w-4 h-4 inline ml-1" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Transactions - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const { deleteTransaction: deleteAction } = useTransactions();
    const { formatCurrency } = useCurrency();
    const { formatDate, formatTime } = useUtils();
    const { success, error: toastError } = useToast();
    const client = useSanctumClient();

    const quickFilter = ref("all");
    const searchQuery = ref("");
    const transactionFilterType = ref("all");
    const accountName = ref("all");
    const categoryName = ref("all");
    const sortBy = ref("date-desc");
    const isDeleting = ref(false);
    const isSearching = ref(false);
    const isClearing = ref(false);

    const route = useRoute();
    const router = useRouter();

    if (route.query.q) {
        searchQuery.value = route.query.q;
    }

    if (route.query.type) {
        transactionFilterType.value = route.query.type;
    }

    if (route.query.accountName) {
        accountName.value = route.query.accountName;
    }

    if (route.query.categoryName) {
        categoryName.value = route.query.categoryName;
    }

    if (route.query.filter) {
        quickFilter.value = route.query.filter;
    }

    if (route.query.sort) {
        sortBy.value = route.query.sort;
    }

    const page = computed(() => {
        const page = parseInt(route.query.page) || 1;
        return page > 0 ? page : 1;
    });

    const {
        data: transactionsData,
        status,
        error,
        refresh,
    } = await useAsyncData("transactions", () =>
        client("/api/transactions", {
            params: {
                search: searchQuery.value || undefined,
                type: transactionFilterType.value || "all",
                accountName: accountName.value || "all",
                categoryName: categoryName.value || "all",
                filter: quickFilter.value !== "all" ? quickFilter.value : undefined,
                sort: sortBy.value || "date-desc",
                page: page.value,
                per_page: 50,
            },
        })
    );

    const categories = computed(() => {
        if (!transactionsData.value?.categories) return [];
        return transactionsData.value.categories.map((category) => category.name);
    });

    const accounts = computed(() => {
        if (!transactionsData.value?.accounts) return [];
        return transactionsData.value.accounts.map((account) => account.name);
    });

    // Computed property to check if any filter is active
    const hasActiveFilters = computed(() => {
        return searchQuery.value || transactionFilterType.value !== "all" || accountName.value !== "all" || categoryName.value !== "all" || quickFilter.value !== "all";
    });

    const handleSearch = async () => {
        isSearching.value = true;
        const query = { ...route.query };
        if (searchQuery.value) {
            query.q = searchQuery.value;
        } else {
            delete query.q;
        }

        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handletransactionFilterType = async () => {
        isSearching.value = true;
        const query = { ...route.query };

        if (transactionFilterType.value && transactionFilterType.value !== "all") {
            query.type = transactionFilterType.value;
        } else {
            delete query.type;
        }

        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handleAccountNameFilter = async () => {
        isSearching.value = true;
        const query = { ...route.query };

        if (accountName.value && accountName.value !== "all") {
            query.accountName = accountName.value;
        } else {
            delete query.accountName;
        }

        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handleCategoryNameFilter = async () => {
        isSearching.value = true;
        const query = { ...route.query };

        if (categoryName.value && categoryName.value !== "all") {
            query.categoryName = categoryName.value;
        } else {
            delete query.categoryName;
        }

        // Reset to first page when changing category filter
        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handleQuickFilter = async (filter) => {
        isSearching.value = true;
        quickFilter.value = filter;
        const query = { ...route.query };

        if (filter && filter !== "all") {
            query.filter = filter;
        } else {
            delete query.filter;
        }

        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const handleSort = async () => {
        isSearching.value = true;
        const query = { ...route.query };

        if (sortBy.value && sortBy.value !== "date-desc") {
            query.sort = sortBy.value;
        } else {
            delete query.sort;
        }

        query.page = 1;

        await router.push({ query });
        await refresh();
        isSearching.value = false;
    };

    const clearSearch = async () => {
        isClearing.value = true;
        searchQuery.value = "";
        transactionFilterType.value = "all";
        accountName.value = "all";
        categoryName.value = "all";
        quickFilter.value = "all";
        sortBy.value = "date-desc";
        await router.push({ query: {} });
        await refresh();
        isClearing.value = false;
    };

    const changePage = async (page) => {
        if (page < 1 || page > transactionsData.value.pagination.last_page) return;

        const query = { ...route.query, page };
        await router.push({ query });
        await refresh();
    };

    const getPageNumbers = computed(() => {
        if (!transactionsData.value?.pagination) return [];

        const current = transactionsData.value.pagination.current_page;
        const last = transactionsData.value.pagination.last_page;
        const delta = 1;
        const range = [];

        for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
            range.push(i);
        }

        if (current - delta > 2) {
            range.unshift("...");
        }

        if (current + delta < last - 1) {
            range.push("...");
        }

        if (last > 1) {
            range.unshift(1);
            range.push(last);
        } else {
            range.unshift(1);
        }

        return range;
    });

    const deleteTransaction = async (id) => {
        if (confirm("Are you sure you want to delete this transaction?")) {
            try {
                isDeleting.value = true;
                await deleteAction(id);
                success("Transaction deleted!");
                await refresh();
            } catch (err) {
                console.log(err);
                toastError("Transaction fail to delete.");
            } finally {
                isDeleting.value = false;
            }
        }
    };
</script>
