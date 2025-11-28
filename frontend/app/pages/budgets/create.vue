<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Create Budget</h1>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Category Selection -->
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

                    <!-- Budget Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2"> Budget Amount (MYR) <span class="text-red-500">*</span> </label>
                        <input v-model="form.amount" type="number" step="0.01" required placeholder="1500.00" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        <p v-if="errors.amount" class="text-red-500 text-sm mt-1">{{ errors.amount }}</p>
                    </div>

                    <!-- Month & Year Selection -->
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
                            {{ formatDateRange() }}
                        </p>
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

    // State
    const form = ref({
        category_id: "",
        amount: "",
        month: "",
        year: new Date().getFullYear(), // Default to current year
    });

    const errors = ref({});
    const loading = ref(false);

    // Months array
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    // Generate years (current year - 1 to current year + 2)
    const years = computed(() => {
        const currentYear = new Date().getFullYear();
        return Array.from({ length: 4 }, (_, i) => currentYear - 1 + i);
    });

    // Dummy categories (replace with API call)
    const categories = ref([
        { id: 1, name: "Food & Groceries" },
        { id: 2, name: "Transportation" },
        { id: 3, name: "Entertainment" },
        { id: 4, name: "Shopping" },
        { id: 5, name: "Healthcare" },
        { id: 6, name: "Utilities" },
        { id: 7, name: "Education" },
        { id: 8, name: "Dining Out" },
    ]);

    // Format date range display
    const formatDateRange = () => {
        if (!form.value.month || !form.value.year) return "";

        const startDate = new Date(form.value.year, form.value.month - 1, 1);
        const endDate = new Date(form.value.year, form.value.month, 0);

        const options = { year: "numeric", month: "long", day: "numeric" };
        return `${startDate.toLocaleDateString("en-US", options)} - ${endDate.toLocaleDateString("en-US", options)}`;
    };

    // Submit form
    const submitForm = async () => {
        try {
            loading.value = true;
            errors.value = {};

            // Calculate start_date and end_date
            const startDate = new Date(form.value.year, form.value.month - 1, 1);
            const endDate = new Date(form.value.year, form.value.month, 0);

            const payload = {
                category_id: form.value.category_id,
                amount: form.value.amount,
                period: form.value.period,
                start_date: startDate.toISOString().split("T")[0], // Format: YYYY-MM-DD
                end_date: endDate.toISOString().split("T")[0],
            };

            // TODO: Replace with actual API call
            console.log("Submitting budget:", payload);

            // const response = await $fetch('/api/budgets', {
            //     method: 'POST',
            //     body: payload,
            // });

            // Simulate success
            await new Promise((resolve) => setTimeout(resolve, 1000));

            // Redirect to budgets page
            navigateTo("/budgets");
        } catch (error) {
            if (error.response?.data?.errors) {
                errors.value = error.response.data.errors;
            }
            console.error("Error creating budget:", error);
        } finally {
            loading.value = false;
        }
    };
</script>
