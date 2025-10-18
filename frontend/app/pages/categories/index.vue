<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                        <p class="text-gray-600 mt-2">Manage your expense and income categories</p>
                    </div>
                    <NuxtLink to="/categories/create" class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                        <Icon name="heroicons:plus" class="w-5 h-5" />
                    </NuxtLink>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="flex border-b border-gray-200">
                    <button
                        :class="[activeTab === 'expense' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'expense'">
                        Expense Categories
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'expense' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">{{ expenseCategories.length }}</span>
                    </button>
                    <button
                        :class="[activeTab === 'income' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'income'">
                        Income Categories
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'income' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">{{ incomeCategories.length }}</span>
                    </button>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-1 relative">
                        <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search categories..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" >
                    </div>
                    <select v-model="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="name">Sort by Name</option>
                        <option value="usage">Sort by Usage</option>
                        <option value="recent">Recently Added</option>
                    </select>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="category in filteredCategories" :key="category.id" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div :style="{ backgroundColor: category.color + '20' }" class="w-12 h-12 rounded-lg flex items-center justify-center">
                                <Icon :name="category.icon" class="w-6 h-6" :style="{ color: category.color }" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ category.name }}</h3>
                                <p class="text-sm text-gray-500">{{ category.transactionCount }} transactions</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <NuxtLink :to="`/categories/edit/${category.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-gray-50 transition-colors">
                                <Icon name="heroicons:pencil" class="w-4 h-4" />
                            </NuxtLink>
                            <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" @click="deleteCategory(category.id)">
                                <Icon name="heroicons:trash" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Usage Stats -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Total Amount</span>
                            <span class="font-semibold text-gray-900">{{ formatCurrency(category.totalAmount) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div :style="{ width: category.usagePercentage + '%', backgroundColor: category.color }" class="h-2 rounded-full transition-all"/>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ category.usagePercentage }}% of total</span>
                            <span v-if="category.isDefault" class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full">Default</span>
                        </div>
                    </div>

                    <!-- Subcategories -->
                    <div v-if="category.subcategories && category.subcategories.length > 0" class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Subcategories:</p>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="sub in category.subcategories" :key="sub.id" class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">{{ sub.name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredCategories.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <Icon name="heroicons:folder-open" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No categories found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your search or create a new category</p>
                <NuxtLink to="/categories/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                    <Icon name="heroicons:plus" class="w-5 h-5" />
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Categories - Expenso",
    });

    // Dummy Data
    const expenseCategories = ref([
        {
            id: 1,
            name: "Food & Groceries",
            icon: "heroicons:shopping-bag",
            color: "#10B981",
            type: "expense",
            transactionCount: 45,
            totalAmount: 2500.0,
            usagePercentage: 25,
            isDefault: true,
            subcategories: [
                { id: 11, name: "Supermarket" },
                { id: 12, name: "Restaurants" },
            ],
        },
        {
            id: 2,
            name: "Transportation",
            icon: "heroicons:truck",
            color: "#3B82F6",
            type: "expense",
            transactionCount: 32,
            totalAmount: 1800.0,
            usagePercentage: 18,
            isDefault: true,
            subcategories: [
                { id: 21, name: "Fuel" },
                { id: 22, name: "Public Transport" },
            ],
        },
        {
            id: 3,
            name: "Entertainment",
            icon: "heroicons:film",
            color: "#F59E0B",
            type: "expense",
            transactionCount: 28,
            totalAmount: 1200.0,
            usagePercentage: 12,
            isDefault: true,
            subcategories: [],
        },
        {
            id: 4,
            name: "Shopping",
            icon: "heroicons:shopping-cart",
            color: "#EC4899",
            type: "expense",
            transactionCount: 24,
            totalAmount: 1500.0,
            usagePercentage: 15,
            isDefault: false,
            subcategories: [],
        },
        {
            id: 5,
            name: "Healthcare",
            icon: "heroicons:heart",
            color: "#EF4444",
            type: "expense",
            transactionCount: 15,
            totalAmount: 900.0,
            usagePercentage: 9,
            isDefault: true,
            subcategories: [],
        },
        {
            id: 6,
            name: "Utilities",
            icon: "heroicons:bolt",
            color: "#8B5CF6",
            type: "expense",
            transactionCount: 12,
            totalAmount: 800.0,
            usagePercentage: 8,
            isDefault: true,
            subcategories: [],
        },
    ]);

    const incomeCategories = ref([
        {
            id: 101,
            name: "Salary",
            icon: "heroicons:banknotes",
            color: "#10B981",
            type: "income",
            transactionCount: 12,
            totalAmount: 48000.0,
            usagePercentage: 80,
            isDefault: true,
            subcategories: [],
        },
        {
            id: 102,
            name: "Freelance",
            icon: "heroicons:briefcase",
            color: "#3B82F6",
            type: "income",
            transactionCount: 8,
            totalAmount: 8000.0,
            usagePercentage: 13,
            isDefault: false,
            subcategories: [],
        },
        {
            id: 103,
            name: "Investments",
            icon: "heroicons:chart-bar",
            color: "#F59E0B",
            type: "income",
            transactionCount: 5,
            totalAmount: 4000.0,
            usagePercentage: 7,
            isDefault: true,
            subcategories: [],
        },
    ]);

    // State
    const activeTab = ref("expense");
    const searchQuery = ref("");
    const sortBy = ref("name");

    // Computed
    const filteredCategories = computed(() => {
        let categories = activeTab.value === "expense" ? expenseCategories.value : incomeCategories.value;

        // Filter by search
        if (searchQuery.value) {
            categories = categories.filter((cat) => cat.name.toLowerCase().includes(searchQuery.value.toLowerCase()));
        }

        // Sort
        if (sortBy.value === "name") {
            categories = [...categories].sort((a, b) => a.name.localeCompare(b.name));
        } else if (sortBy.value === "usage") {
            categories = [...categories].sort((a, b) => b.transactionCount - a.transactionCount);
        }

        return categories;
    });

    // Methods
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat("en-MY", {
            style: "currency",
            currency: "MYR",
        }).format(amount);
    };

    const deleteCategory = (id) => {
        if (confirm("Are you sure you want to delete this category?")) {
            if (activeTab.value === "expense") {
                expenseCategories.value = expenseCategories.value.filter((c) => c.id !== id);
            } else {
                incomeCategories.value = incomeCategories.value.filter((c) => c.id !== id);
            }
        }
    };
</script>
