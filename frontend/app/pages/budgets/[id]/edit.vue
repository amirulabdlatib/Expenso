<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Budget</h1>

                <!-- Loading State -->
                <div v-if="status === 'pending'" class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                    <span class="ml-3 text-gray-600">Loading budget...</span>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-sm font-medium text-red-800">Failed to load budget</p>
                    <p class="text-sm text-red-600 mt-1">{{ error.statusMessage || "Unable to load budget details" }}</p>
                    <button class="mt-3 px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors" @click="refresh()">Retry</button>
                </div>

                <!-- Form -->
                <form v-else class="space-y-6" @submit.prevent="updateForm">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Month <span class="text-red-500">*</span> </label>
                            <select v-model="form.month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" @change="onPeriodChange">
                                <option value="">Select month</option>
                                <option v-for="(month, index) in months" :key="index" :value="index + 1">
                                    {{ month }}
                                </option>
                            </select>
                            <p v-if="errors.month" class="text-red-500 text-sm mt-1">{{ errors.month[0] }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Year <span class="text-red-500">*</span> </label>
                            <select v-model="form.year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" @change="onPeriodChange">
                                <option value="">Select year</option>
                                <option v-for="year in years" :key="year" :value="year">
                                    {{ year }}
                                </option>
                            </select>
                            <p v-if="errors.year" class="text-red-500 text-sm mt-1">{{ errors.year[0] }}</p>
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
                        <select v-model="form.category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" :disabled="loadingCategories || !categories.length">
                            <option value="">Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <p v-if="loadingCategories" class="text-gray-600 text-sm mt-1">Loading categories...</p>
                        <p v-else-if="!categories.length && form.month && form.year" class="text-amber-600 text-sm mt-1">No available categories. All categories already have budgets for this period.</p>
                        <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">{{ errors.category_id[0] }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"> Budget Amount (MYR) <span class="text-red-500">*</span> </label>
                        <MoneyInput v-model="form.amount" :is-loading="loading" />
                        <p v-if="errors.amount" class="text-red-500 text-sm mt-1">{{ errors.amount[0] }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <NuxtLink to="/budgets" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"> Cancel </NuxtLink>
                        <button type="submit" :disabled="loading || !categories.length" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50">
                            {{ loading ? "Updating..." : "Update Budget" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Edit Budget - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth", "verified"],
    });

    const route = useRoute();
    const { months, formatDateRange } = useUtils();
    const { getBudgetCategories, updateBudget, errors } = useBudget();
    const { success, error: errorToast } = useToast();

    const categories = ref([]);
    const loading = ref(false);
    const loadingCategories = ref(false);

    const { data, status, error, refresh } = await useSanctumFetch(`/api/budgets/${route.params.id}/edit`);

    const form = reactive({
        category_id: "",
        amount: null,
        month: null,
        year: null,
    });

    const years = computed(() => {
        const currentYear = new Date().getFullYear();
        return Array.from({ length: 2 }, (_, i) => currentYear + i);
    });

    const loadCategories = async () => {
        if (!form.month || !form.year) {
            categories.value = [];
            return;
        }

        loadingCategories.value = true;
        try {
            const response = await getBudgetCategories(form.month, form.year, route.params.id);
            categories.value = response;
        } catch (err) {
            console.error("Failed to load categories:", err);
        } finally {
            loadingCategories.value = false;
        }
    };

    // Populate form with budget data
    watch(
        () => data.value,
        (newData) => {
            if (newData?.budget) {
                form.category_id = newData.budget.category_id;
                form.amount = newData.budget.amount;
                form.month = newData.budget.month;
                form.year = newData.budget.year;

                // Load categories after populating form
                loadCategories();
            }
        },
        { immediate: true }
    );

    const onPeriodChange = () => {
        form.category_id = "";
        categories.value = [];
        loadCategories();
    };

    const updateForm = async () => {
        loading.value = true;

        try {
            await updateBudget(form, route.params.id);
            success("Budget updated.");
            navigateTo("/budgets");
        } catch (err) {
            console.error(err);
            errorToast("Budget failed to update.");
        } finally {
            loading.value = false;
        }
    };
</script>
