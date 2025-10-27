<template>
    <div class="min-h-screen bg-gray-50 pt-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <CreateTransactionHeader />

            <!-- Loading State -->
            <div v-if="isFetchingAccounts" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600" />
                <p class="mt-4 text-gray-600">Loading...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="errors" class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                <div class="flex items-start space-x-4">
                    <Icon name="heroicons:exclamation-circle" class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" />
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-red-900 mb-2">Error Loading Data</h3>
                        <p class="text-red-800 mb-4">
                            {{ errors.statusMessage || "Failed to load accounts. Please try again." }}
                        </p>
                        <button class="inline-flex items-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium" @click="retryFetch">
                            <Icon name="heroicons:arrow-path" class="w-5 h-5" />
                            <span>Retry</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div v-else>
                <NoAccountsAlert v-if="accounts.length === 0" />

                <!-- Form Card -->
                <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <form class="space-y-6" @submit.prevent="handleSubmit">
                        <!-- Account -->
                        <div>
                            <label for="account" class="block text-sm font-medium text-gray-700 mb-2"> Account <span class="text-red-500">*</span> </label>
                            <select id="account" v-model.number="form.account_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                <option value="" disabled>Select an account</option>
                                <option v-for="account in accounts" :key="account.id" :value="account.id">{{ account.name }}</option>
                            </select>
                        </div>

                        <!-- Transaction Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2"> Transaction Name <span class="text-red-500">*</span> </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Grocery Shopping"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                required
                            />
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2"> Description (Optional) </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                placeholder="Add any notes about this transaction..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                            />
                        </div>

                        <!-- Transaction Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"> Transaction Type <span class="text-red-500">*</span> </label>
                            <div class="grid grid-cols-3 gap-3">
                                <button
                                    type="button"
                                    class="relative p-4 border-2 rounded-lg transition-all hover:border-red-500"
                                    :class="form.type === 'expense' ? 'border-red-500 bg-red-50' : 'border-gray-200'"
                                    @click="form.type = 'expense'"
                                >
                                    <Icon name="heroicons:arrow-trending-down" class="w-6 h-6 mx-auto mb-2" :class="form.type === 'expense' ? 'text-red-500' : 'text-gray-400'" />
                                    <span class="text-sm font-medium block" :class="form.type === 'expense' ? 'text-red-700' : 'text-gray-700'">Expense</span>
                                </button>

                                <button
                                    type="button"
                                    class="relative p-4 border-2 rounded-lg transition-all hover:border-green-500"
                                    :class="form.type === 'income' ? 'border-green-500 bg-green-50' : 'border-gray-200'"
                                    @click="form.type = 'income'"
                                >
                                    <Icon name="heroicons:arrow-trending-up" class="w-6 h-6 mx-auto mb-2" :class="form.type === 'income' ? 'text-green-500' : 'text-gray-400'" />
                                    <span class="text-sm font-medium block" :class="form.type === 'income' ? 'text-green-700' : 'text-gray-700'">Income</span>
                                </button>

                                <button
                                    type="button"
                                    class="relative p-4 border-2 rounded-lg transition-all"
                                    :class="[form.type === 'transfer' ? 'border-blue-500 bg-blue-50' : 'border-gray-200', accounts.length > 1 && form.account_id ? 'hover:border-blue-500 cursor-pointer' : 'opacity-50 cursor-not-allowed']"
                                    :disabled="accounts.length <= 1 || !form.account_id"
                                    @click="accounts.length > 1 && form.account_id && (form.type = 'transfer')"
                                >
                                    <Icon name="heroicons:arrow-path" class="w-6 h-6 mx-auto mb-2" :class="form.type === 'transfer' ? 'text-blue-500' : 'text-gray-400'" />
                                    <span class="text-sm font-medium block" :class="form.type === 'transfer' ? 'text-blue-700' : 'text-gray-700'">Transfer</span>
                                </button>
                            </div>
                        </div>

                        <!-- Categories dropdown -->
                        <div v-if="form.type !== 'transfer' && form.type !== ''">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2"> Category <span class="text-red-500">*</span> </label>

                            <!-- Loading Categories -->
                            <div v-if="isFetchingCategories" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">Loading categories...</div>

                            <!-- Category Select -->
                            <select v-else id="category" v-model.number="form.category_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                <option value="" disabled>Select a category</option>
                                <option v-for="category in filteredCategories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Related Account (for transfers) -->
                        <div v-if="form.type === 'transfer'">
                            <label for="related_account" class="block text-sm font-medium text-gray-700 mb-2"> To Account <span class="text-red-500">*</span> </label>
                            <select
                                id="related_account"
                                v-model.number="form.related_account_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                :required="form.type === 'transfer'"
                            >
                                <option value="" disabled>Select destination account</option>
                                <option v-for="account in transferredAccounts" :key="account.id" :value="account.id">{{ account.name }}</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2"> Amount <span class="text-red-500">*</span> </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium text-lg">MYR</span>
                                <input
                                    id="amount"
                                    v-model.number="form.amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg font-medium"
                                    required
                                    @keydown="if (['-', '+', 'e', 'E'].includes($event.key)) $event.preventDefault();"
                                />
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2"> Date <span class="text-red-500">*</span> </label>
                                <input id="date" v-model="form.date" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required />
                            </div>

                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-2"> Time <span class="text-red-500">*</span> </label>
                                <input id="time" v-model="form.time" type="time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col-reverse md:flex-row md:items-center md:justify-end gap-3 pt-6 border-t border-gray-200">
                            <NuxtLink to="/transactions" class="w-full md:w-auto px-6 py-3 text-center border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium"> Cancel </NuxtLink>
                            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center space-x-2">
                                <span>Create Transaction</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    useHead({
        title: "Create Transaction - Expenso",
    });

    definePageMeta({
        middleware: ["sanctum:auth"],
    });

    const now = new Date();
    const isFetchingAccounts = ref(false);
    const isFetchingCategories = ref(false);
    const form = reactive({
        type: "",
        name: "",
        amount: 0,
        date: now.toISOString().split("T")[0],
        time: now.toTimeString().split(" ")[0].substring(0, 5),
        account_id: "",
        category_id: "",
        related_account_id: "",
        description: "",
    });

    const accounts = ref([]);
    const categories = ref([]);
    const { getTransactionAccounts, getTransactionCategories, errors } = useTransactions();

    const transferredAccounts = computed(() => accounts.value.filter((a) => a.id !== form.account_id));

    const fetchAccounts = async () => {
        isFetchingAccounts.value = true;
        try {
            const response = await getTransactionAccounts();
            accounts.value = response.accounts || [];
        } catch (err) {
            console.error("Error fetching accounts:", err);
        } finally {
            isFetchingAccounts.value = false;
        }
    };

    const fetchCategories = async () => {
        isFetchingCategories.value = true;
        try {
            const response = await getTransactionCategories();
            categories.value = response.categories || [];
        } catch (err) {
            console.error("Error fetching categories:", err);
        } finally {
            isFetchingCategories.value = false;
        }
    };

    const retryFetch = () => {
        fetchAccounts();
        if (categories.value.length === 0 && form.type !== "transfer" && form.type !== "") {
            fetchCategories();
        }
    };

    watch(
        () => form.type,
        (newType) => {
            if (newType !== "transfer" && newType !== "" && categories.value.length === 0) {
                fetchCategories();
            }
            if (newType === "transfer") {
                form.category_id = "";
            }
        }
    );

    const filteredCategories = computed(() => {
        return categories.value.filter((category) => category.type === form.type);
    });

    onMounted(() => {
        fetchAccounts();
    });

    const handleSubmit = () => {
        const datetime = `${form.date} ${form.time}:00`;
        const { date, time, ...formData } = form;
        const data = { ...formData, datetime };

        console.log("Form submitted:", data);
    };
</script>
