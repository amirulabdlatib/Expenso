<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Budget</h1>

                <form class="space-y-6" @submit.prevent="submitForm">
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
    const today = new Date();
    const categories = ref([]);

    const form = ref({
        category_id: null,
        amount: null,
        month: today.getMonth() + 1,
        year: today.getFullYear(),
    });

    const errors = ref({});
    const loading = ref(false);

    // Generate years (current year - 1 to current year + 2)
    const years = computed(() => {
        const currentYear = new Date().getFullYear();
        return Array.from({ length: 4 }, (_, i) => currentYear - 1 + i);
    });

    // Submit form
    const submitForm = async () => {};
</script>
