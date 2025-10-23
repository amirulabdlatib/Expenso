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
                        <Icon name="heroicons:plus" class="w-2 h-2" />
                    </NuxtLink>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="flex border-b border-gray-200">
                    <button
                        :class="[activeTab === 'all' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'all'">
                        All
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'all' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                            {{ categories?.length || 0 }}
                        </span>
                    </button>
                    <button
                        :class="[activeTab === 'expense' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'expense'">
                        Expenses
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'expense' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                            {{ expenseCategories.length }}
                        </span>
                    </button>
                    <button
                        :class="[activeTab === 'income' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700', 'px-6 py-4 border-b-2 font-medium text-sm transition-colors']"
                        @click="activeTab = 'income'">
                        Income
                        <span class="ml-2 px-2 py-1 text-xs rounded-full" :class="activeTab === 'income' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                            {{ incomeCategories.length }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Search and Filter (UI only, no functionality) -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-1 relative">
                        <Icon name="heroicons:magnifying-glass" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                        <input type="text" placeholder="Search categories..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="status === 'pending'" class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <Icon name="heroicons:exclamation-triangle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-red-900 mb-2">Error loading categories</h3>
                <p class="text-red-600 mb-4">{{ error.message }}</p>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" @click="refresh()">Try Again</button>
            </div>

            <!-- Categories Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="category in displayedCategories" :key="category.id" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div :class="`bg-${category.color}-100`" class="w-12 h-12 rounded-lg flex items-center justify-center">
                                <Icon :name="category.icon" class="w-6 h-6" :class="`text-${category.color}-600`" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ category.name }}</h3>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full" :class="category.type === 'expense' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'">
                                    {{ capitalizeWord(category.type) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <NuxtLink :to="`/categories/${category.id}`" class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                                <Icon name="heroicons:pencil" class="w-4 h-4" />
                            </NuxtLink>
                            <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" @click="handleDelete(category.id)">
                                <Icon name="heroicons:trash" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <!-- Subcategories -->
                    <div v-if="category.children && category.children.length > 0" class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Subcategories:</p>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="sub in category.children" :key="sub.id" class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                {{ sub.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="status === 'success' && displayedCategories.length === 0" class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <Icon name="heroicons:folder-open" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No categories found</h3>
                <p class="text-gray-600 mb-6">Get started by creating your first category</p>
                <NuxtLink to="/categories/create" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                    <Icon name="heroicons:plus" class="w-5 h-5" />
                    <span>Create Category</span>
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Categories - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const activeTab = ref("all");
    const { success, error: toastError } = useToast();
    const client = useSanctumClient();

    const { data: categoriesData, status, error, refresh } = await useAsyncData("categories", () => client("api/categories"));
    const categories = computed(() => categoriesData.value?.categories || []);

    const expenseCategories = computed(() => {
        return categories.value.filter((cat) => cat.type === "expense");
    });

    const incomeCategories = computed(() => {
        return categories.value.filter((cat) => cat.type === "income");
    });

    const displayedCategories = computed(() => {
        if (activeTab.value === "all") {
            return categories.value;
        } else if (activeTab.value === "expense") {
            return expenseCategories.value;
        } else {
            return incomeCategories.value;
        }
    });

    function capitalizeWord(text) {
        if (!text) return "";
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    const handleDelete = async (id) => {
        if (!confirm("Are you sure you want to delete this category?")) {
            return;
        }

        try {
            await client(`api/categories/${id}`, {
                method: "DELETE",
            });
            success("Category deleted successfully.");
            refresh();
        } catch (err) {
            console.error("Delete failed:", err);
            toastError("Failed to delete category!");
        }
    };
</script>
