<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Budget</h1>

                <!-- Loading State -->
                <div v-if="loadingCategories" class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600">Loading categories...</span>
                </div>

                <!-- Error State -->
                <div v-else-if="categoryError" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-sm font-medium text-red-800">Failed to load categories</p>
                    <p class="text-sm text-red-600 mt-1">{{ categoryError }}</p>
                    <button class="mt-3 px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors" @click="retryLoadCategories">Retry</button>
                </div>

                <!-- Form -->
                <form v-else class="space-y-6" @submit.prevent="submitForm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Month <span class="text-red-500">*</span> </label>
                            <select v-model="form.month" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select month</option>
                                <option v-for="(month, index) in months" :key="index" :value="index + 1">
                                    {{ month }}
                                </option>
                            </select>
                            <p v-if="errors.month" class="text-red-500 text-sm mt-1">{{ errors.month }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Year <span class="text-red-500">*</span> </label>
                            <select v-model="form.year" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select year</option>
                                <option v-for="year in years" :key="year" :value="year">
                                    {{ year }}
                                </option>
                            </select>
                            <p v-if="errors.year" class="text-red-500 text-sm mt-1">{{ errors.year }}</p>
                        </div>
                    </div>

                    <!-- Date Range Display (Auto-calculated) -->
                    <div v-if="form.month && form.year" class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-indigo-900 mb-1">Budget Period</p>
                        <p class="text-sm text-indigo-700">
                            {{ formatDateRange(form.month, form.year) }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"> Category <span class="text-red-500">*</span> </label>
                        <select v-model="form.category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">{{ errors.category_id }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"> Budget Amount (MYR) <span class="text-red-500">*</span> </label>
                        <input v-model="form.amount" type="number" step="0.01" required placeholder="1500.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        <p v-if="errors.amount" class="text-red-500 text-sm mt-1">{{ errors.amount }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <NuxtLink to="/budgets" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"> Cancel </NuxtLink>
                        <button type="submit" :disabled="loading" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50">
                            {{ loading ? "Creating..." : "Create Budget" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Create Budget - Expenso",
    });

    const { months, formatDateRange } = useUtils();
    const { getBudgetCategories, errors } = useBudgets();
    const today = new Date();
    const categories = ref([]);
    const loading = ref(false);
    const loadingCategories = ref(false);
    const categoryError = ref(null);

    const loadCategories = async () => {
        loadingCategories.value = true;
        categoryError.value = null;
        try {
            const response = await getBudgetCategories();
            categories.value = response;
        } catch (err) {
            categoryError.value = err.statusMessage;
        } finally {
            loadingCategories.value = false;
        }
    };

    const retryLoadCategories = () => {
        loadCategories();
    };

    onMounted(() => {
        loadCategories();
    });

    const form = ref({
        category_id: "",
        amount: null,
        month: today.getMonth() + 1,
        year: today.getFullYear(),
    });

    // Generate years (current year - 1 to current year + 2)
    const years = computed(() => {
        const currentYear = new Date().getFullYear();
        return Array.from({ length: 4 }, (_, i) => currentYear - 1 + i);
    });

    const submitForm = async () => {};
</script>
